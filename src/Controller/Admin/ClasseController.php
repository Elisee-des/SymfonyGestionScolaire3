<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\Classe;
use App\Form\Classe\CreerClasseListeType;
use App\Form\Classe\CreerClasseMatiereType;
use App\Form\Classe\CreerClasseType;
use App\Form\Classe\EditerClasseListeType;
use App\Form\Classe\EditerClasseType;
use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * @Route("/admin/classe", name="admin_classe_")
 */
class ClasseController extends AbstractController
{
    /**
     * @Route("/", name="annee")
     */
    public function index(AnneeRepository $anneeRepository, CacheInterface $cacheInterface): Response
    {
        $annees = $cacheInterface->get("classe_annee", function (ItemInterface $itemInterface) use ($anneeRepository) {
            $itemInterface->expiresAfter(700000);
            return $anneeRepository->findAll();
        });

        return $this->render('admin/classe/index.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/listeDesClasse/{id}", name="liste_des_classes")
     */
    public function listeClasse(Annee $annee): Response
    {
        // $annees = $anneeRepository->findAll();

        return $this->render('admin/classe/listeClasse.html.twig', [
            'classes' => $annee->getClasses(),
            'annee' => $annee
        ]);
    }

    /**
     * @Route("/listeDesEleve/{id}", name="liste_des_eleves")
     */
    public function listeEleve(Request $request, Classe $classe): Response
    {
        $eleves = $classe->getEleves();
        if (isset($_POST["export"])) {
            $type_fichier = $request->get("file_type");

            $fichier = new Spreadsheet();

            $active_feuille = $fichier->getActiveSheet();

            $active_feuille->setCellValue("A1", "Non");
            $active_feuille->setCellValue("B1", "Prenom");

            $count = 2;

            foreach ($eleves as $eleve) {
                $active_feuille->setCellValue("A" . $count, $eleve->getNom());
                $active_feuille->setCellValue("B" . $count, $eleve->getPrenom());

                $count = $count + 1;
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($fichier, $type_fichier);

            $nom_fichier = "liste des eleves de a la classe de ". $classe->getNom() . '.' . strtolower($type_fichier);

            $writer->save($nom_fichier);

            header('Content-Type: application/x-www-form-urlencoded');

            header('Content-Transfer-Encoding: Binary');

            header("Content-disposition: attachment; filename=\"" . $nom_fichier . "\"");

            readfile($nom_fichier);

            unlink($nom_fichier);

            return $this->redirectToRoute('admin_classe_liste_des_eleves', ["id"=>$classe->getId()]);
        }

        if (isset($_POST["export2"])) {
            $type_fichier2 = $request->get("file_type2");

            $fichier = new Spreadsheet();

            $active_feuille = $fichier->getActiveSheet();

            $active_feuille->setCellValue("A1", "Non");
            $active_feuille->setCellValue("B1", "Prenom");
            $active_feuille->setCellValue("C1", "Numero");
            $active_feuille->setCellValue("D1", "EleveNom");
            $active_feuille->setCellValue("E1", "ElevePrenom");

            $count = 2;

            foreach ($eleves as $eleve) {
                $active_feuille->setCellValue("A" . $count, $eleve->getUser()->getNom());
                $active_feuille->setCellValue("B" . $count, $eleve->getUser()->getPrenom());
                $active_feuille->setCellValue("C" . $count, $eleve->getUser()->getNumero());
                $active_feuille->setCellValue("D" . $count, $eleve->getNom());
                $active_feuille->setCellValue("E" . $count, $eleve->getPrenom());

                $count = $count + 1;
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($fichier, $type_fichier2);

            $nom_fichier = "liste des parents d'eleve de a la classe de " . $classe->getNom() . '.' . strtolower($type_fichier2);

            $writer->save($nom_fichier);

            header('Content-Type: application/x-www-form-urlencoded');

            header('Content-Transfer-Encoding: Binary');

            header("Content-disposition: attachment; filename=\"" . $nom_fichier . "\"");

            readfile($nom_fichier);

            unlink($nom_fichier);

            return $this->redirectToRoute('admin_classe_liste_des_eleves', ["id" => $classe->getId()]);
        }

        return $this->render('admin/classe/listeEleves.html.twig', [
            'eleves' => $eleves,
            'classe' => $classe
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $classe = new Classe();

        $form = $this->createForm(CreerClasseType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("creer_classe")["nom"];

            $classe->setNom($salle);

            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "classe " . $classe->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_classe_liste');
        }

        return $this->render('admin/classe/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/creer/{id}", name="creer_classe_dans_liste")
     */
    public function creerClasseListe(EntityManagerInterface $entityManager, Request $request, Annee $annee): Response
    {
        $classe = new Classe();

        $form = $this->createForm(CreerClasseListeType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("creer_classe_liste")["nom"];

            $classe->setNom($nom);
            $classe->setAnnee($annee);

            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Classe " . $classe->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_classe_liste_des_classes', ["id" => $annee->getId()]);
        }

        return $this->render('admin/classe/creerClasseListe.html.twig', [
            'form' => $form->createView(),
            'annee' => $annee
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer_classe_dans_liste")
     */
    public function editerClasseListe(EntityManagerInterface $entityManager, Request $request, Classe $classe): Response
    {
        $form = $this->createForm(EditerClasseListeType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("editer_classe_liste")["nom"];

            $classe->setNom($nom);
            $classe->setAnnee($classe->getAnnee());

            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Classe " . $classe->getNom() . " a ete modifié avec succes"
            );

            return $this->redirectToRoute('admin_classe_liste_des_classes', ["id" => $classe->getAnnee()->getId()]);
        }

        return $this->render('admin/classe/editerClasse.html.twig', [
            'form' => $form->createView(),
            'classe' => $classe
        ]);
    }


    /**
     * @Route("/creerPourMatiere/{id}", name="creer_pour_matiere")
     */
    public function creerPourMatiere(EntityManagerInterface $entityManager, Request $request, Annee $annee): Response
    {
        $classe = new Classe();

        $form = $this->createForm(CreerClasseMatiereType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $request->get("creer_classe")["nom"];

            $classe->setNom($nom);
            $classe->setAnnee($annee);

            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Classe " . $classe->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_classe_liste');
        }

        return $this->render('admin/classe/creer.html.twig', [
            'form' => $form->createView(),
            // 'classe' => $classe
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, Classe $classe): Response
    {

        $form = $this->createForm(EditerClasseType::class, $classe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle = $request->get("editer_classe")["nom"];

            $classe->setNom($salle);

            $entityManager->persist($classe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une classe. La nouvelle classe est " . $classe->getNom() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_classe_liste');
        }

        return $this->render('admin/classe/editer.html.twig', [
            'form' => $form->createView(),
            'classe' => $classe
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, Classe $classe): Response
    {
        if (!$classe) {
            throw $this->createNotFoundException(
                "Cette classe n'existe pas"
            );
        }

        $entityManager->remove($classe);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Classe supprimer avec succes"
        );

        return $this->redirectToRoute('admin_classe_liste');
    }
}
