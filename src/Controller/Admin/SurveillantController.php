<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Surveillant\CreerSurveillantType;
use App\Form\Surveillant\EditerMotDePasseSurveillantType;
use App\Form\Surveillant\EditerSurveillantType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/admin/surveillant", name="admin_surveillant_")
 */
class SurveillantController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(Request $request, UserRepository $userRepository, CacheInterface $cacheInterface): Response
    {
        if (isset($_POST["export"])) {
            $type_fichier = $request->get("file_type");
            $surveilantss = $userRepository->findAll();

            foreach ($surveilantss as $surveillant) {
                if($surveillant->getRoles()[0] == "ROLE_SURVEILLANT")
                {
                    $surveilants[] = $surveillant;
                }
            }

            $fichier = new Spreadsheet();

            $active_feuille = $fichier->getActiveSheet();

            $active_feuille->setCellValue("A1", "Non");
            $active_feuille->setCellValue("B1", "Prenom");
            $active_feuille->setCellValue("C1", "Numero");

            $count = 2;

            foreach ($surveilants as $surveillant) {
                    $active_feuille->setCellValue("A" . $count, $surveillant->getNom());
                    $active_feuille->setCellValue("B" . $count, $surveillant->getPrenom());
                    $active_feuille->setCellValue("C" . $count, $surveillant->getNumero());
    
                    $count = $count + 1;
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($fichier, $type_fichier);

            $nom_fichier = "liste des surveillants" . '.' . strtolower($type_fichier);

            $writer->save($nom_fichier);

            header('Content-Type: application/x-www-form-urlencoded');

            header('Content-Transfer-Encoding: Binary');

            header("Content-disposition: attachment; filename=\"" . $nom_fichier . "\"");

            readfile($nom_fichier);

            unlink($nom_fichier);

            return $this->redirectToRoute('admin_surveillant_liste');
        }

        $surveillants = $cacheInterface->get("surveillant_liste", function (ItemInterface $itemInterface) use ($userRepository) {
            $itemInterface->expiresAfter(700000);
            return $userRepository->findAll();
        });

        return $this->render('admin/surveillant/index.html.twig', [
            'surveillants' => $surveillants,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detai(User $surveillant, CacheInterface $cacheInterface): Response
    {
        $surveillant = $cacheInterface->get("surveillant_detail", function (ItemInterface $itemInterface) use ($surveillant) {
            $itemInterface->expiresAfter(700000);
            return $surveillant;
        });

        return $this->render('admin/surveillant/detail.html.twig', [
            'surveillant' => $surveillant,
        ]);
    }


    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordhasher): Response
    {
        $surveillant = new User();

        $form = $this->createForm(CreerSurveillantType::class, $surveillant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = ["ROLE_SURVEILLANT"];

            $nom = $request->get("creer_surveillant")["nom"];
            $prenom = $request->get("creer_surveillant")["prenom"];
            $numero = $request->get("creer_surveillant")["numero"];
            $passwordShow = $request->get("creer_surveillant")["password"]["first"];
            $password = $passwordhasher->hashPassword($surveillant, $passwordShow);
            $email = $request->get("creer_surveillant")["email"];

            $surveillant->setNom($nom);
            $surveillant->setPrenom($prenom);
            $surveillant->setRoles($role);
            $surveillant->setIssurveillant(true);
            $surveillant->setNumero($numero);
            $surveillant->setPassword($password);
            $surveillant->setEmail($email);

            $entityManager->persist($surveillant);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le surveillant ' . $surveillant->getNom() . ' ' .  $surveillant->getPrenom() . " a ete ajouter avec succes a la liste des surveillants"
            );

            return $this->redirectToRoute('admin_surveillant_liste');
        }

        return $this->render('admin/surveillant/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, User $surveillant): Response
    {
        $form = $this->createForm(EditerSurveillantType::class, $surveillant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("editer_surveillant")["nom"];
            $prenom = $request->get("editer_surveillant")["prenom"];
            $numero = $request->get("editer_surveillant")["numero"];
            $email = $request->get("editer_surveillant")["email"];

            $surveillant->setNom($nom);
            $surveillant->setPrenom($prenom);
            $surveillant->setNumero($numero);
            $surveillant->setEmail($email);

            $entityManager->persist($surveillant);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le surveillant ' . $surveillant->getNom() . ' ' .  $surveillant->getPrenom() . " a ete modifier avec succes a la liste des surveillants"
            );

            return $this->redirectToRoute('admin_surveillant_liste');
        }

        return $this->render('admin/surveillant/editer.html.twig', [
            'form' => $form->createView(),
            'surveillant' => $surveillant
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, User $surveillant): Response
    {

        $entityManager->remove($surveillant);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Le surveillant a été supprimer avec succes"
        );

        return $this->redirectToRoute('admin_surveillant_liste');
    }

    /**
     * @Route("/{id}/editer_mot_de_passe", name="editer_mot_de_passe")
     */
    public function editerMotDePasse(EntityManagerInterface $entityManagerInterface, Request $request, User $surveillant, UserPasswordHasherInterface  $passwordhasher): Response
    {
        $form = $this->createForm(EditerMotDePasseSurveillantType::class, $surveillant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordShow = $request->get("editer_mot_de_passe_surveillant")["mdp"]["first"];
            $password = $passwordhasher->hashPassword($surveillant, $passwordShow);

            $surveillant->setPassword($password);

            $entityManagerInterface->persist($surveillant);
            $entityManagerInterface->flush();

            $this->addFlash(
                'success',
                'Le mot de passe de ' . $surveillant->getNom() . " " . $surveillant->getPrenom() . ' a ete modifier avec succes'
            );

            return $this->redirectToRoute('admin_surveillant_detail', ["id" => $surveillant->getId()]);
        }

        return $this->render('admin/surveillant/editerMotDePasee.html.twig', [
            "form" => $form->createView(),
            "surveillant" => $surveillant
        ]);
    }
}
