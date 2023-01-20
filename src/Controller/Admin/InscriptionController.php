<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Trimestre;
use App\Entity\User;
use App\Form\Inscription\EditerInscriptionType;
use App\Form\Inscription\InscriptionType;
use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\TrimestreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/admin", name="admin_")
 */
class InscriptionController extends AbstractController
{
    // /**
    //  * @Route("/liste/inscript", name="inscript_liste")
    //  */
    // public function liste(EleveRepository $eleveRepository): Response
    // {
    //     $eleves = $eleveRepository->findBy([], ["id" => "DESC"]);
    //     return $this->render('admin/inscription/listeInscript.html.twig', [
    //         "eleves" => $eleves
    //     ]);
    // }

    /**
     * @Route("/inscriptionAccueil", name="inscription_accueil")
     */
    public function listeAnnee(EleveRepository $eleveRepository, CacheInterface $cacheInterface): Response
    {
        $eleves = $cacheInterface->get("inscription_accueil", function(ItemInterface $itemInterface) use ($eleveRepository){
            $itemInterface->expiresAfter(700000);
            return $eleveRepository->findBy([], ["id" => "DESC"], 2);
        });

        $form = $this->createFormBuilder()
            ->add("annee", EntityType::class, [
                "class" => Annee::class,
                "label" => "Choisir l'annee"
            ])
            ->add("trimestre", EntityType::class, [
                "class" => Trimestre::class,
                "label" => "Choisir le trimestre de l'annee"
            ])
            ->add("classe", EntityType::class, [
                "class" => Classe::class,
                "label" => "Choisir la classe de l'eleve"
            ])
            ->add("Continuer", SubmitType::class)
            ->setAction($this->generateUrl("admin_inscription"))
            ->getForm();

        return $this->render('admin/inscription/index.html.twig', [
            'form' => $form->createView(),
            "eleves" => $eleves
        ]);
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(
        Request $request,
        ClasseRepository $classeRepository,
        AnneeRepository $anneeRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        //on recupere les donnees du formulaire
        $idAnnee = $request->get("form")["annee"];
        $idTrimestre = $request->get("form")["trimestre"];
        $idClasse = $request->get("form")["classe"];

        //on recupper les donnee de la base de donnee
        $class = $classeRepository->find($idClasse);
        $anne = $anneeRepository->find($idAnnee);
        //Ici pas besoin, je veux juste gener un formulaire

        //On genere le formulaire
        $formBuilder = $this->createFormBuilder()
            ->add("nom_parent", TextType::class, [
                "mapped" => false,
                'label' => "Le nom du parent (*Obligatoire)"
            ])
            ->add("prenom_parent", TextType::class, [
                "mapped" => false,
                'label' => "Le prenom du parent (*Obligatoire)"
            ])
            ->add("numero_parent", NumberType::class, [
                "mapped" => false,
                'label' => "Le numero du parent (*Obligatoire)"
            ])
            ->add('password', RepeatedType::class, [
                "mapped" => false,
                "type" => PasswordType::class,
                "first_options" => [
                    "label" => "Nouveau mot de passe (*Obligatoire)",
                    'attr' => ['autocomplete' => 'new-password']
                ],
                "second_options" => [
                    "label" => "Repeter le mot de passe (*Obligatoire) ",
                    'attr' => ['autocomplete' => 'new-password']
                ],
                "invalid_message" => "Mot de passe non identique",
            ])
            ->add("email_parent", TextType::class, [
                "mapped" => false,
                'label' => "Email du parent (*Facultatif)"
            ])
            // ->add("")            
            ->add("nom_eleve", TextType::class, [
                "mapped" => false,
                'label' => "Nom de l'eleve (*Obligatoire)"
            ])
            ->add("prenom_eleve", TextType::class, [
                "mapped" => false,
                'label' => "Le prenom de l'eleve (*Obligatoire)"
            ])
            ->add("is_redoublant", ChoiceType::class, [
                "mapped" => false,
                'label' => "Est t-il un(e) redoublant(e) ? (*Obligatoire)",
                'choices' => [
                    "Non" => "1",
                    "Oui" => "2"
                ]
            ])
            ->add("ancien_lycee", TextType::class, [
                "mapped" => false,
                'label' => "Le nom de l'ancien lyceé (*Obligatoire)"
            ])
            ->add("numero_eleve", NumberType::class, [
                "mapped" => false,
                'label' => "Le numero de l'eleve (*Facultatif)"
            ])
            ->add("email_eleve", EmailType::class, [
                "mapped" => false,
                'label' => "L'email de l'eleve (*Facultatif)"
            ])
            ->add("ape", NumberType::class, [
                "mapped" => false,
                'label' => "Le montant de l'ape"
            ])
            ->add("frais", NumberType::class, [
                "mapped" => false,
                'label' => "Le montant des frais de scolarité payé a l'inscription"
            ])
            ->add('is_rgpd', CheckboxType::class, [
                "label" => "Cette eleve inscript s'engage a ce soumettre au reglement interieur"
            ])
            ->add("annee", HiddenType::class, [
                'attr' => ["value" => $idAnnee],
            ])
            ->add("trimestre", HiddenType::class, [
                'attr' => ["value" => $idTrimestre],
            ])
            ->add("classe", HiddenType::class, [
                'attr' => ["value" => $idClasse],
                'label' => "Choisir la classe de l'eleve (*Obligatoire)"
            ])
            ->add("Inscription", SubmitType::class, [
                "label" => "Terminer l'inscription"
            ]);

        $form = $formBuilder->getForm();

        //On fais le handlrequest

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $parentCreer = new User();
            $nomParent = $request->get("form")["nom_parent"];
            $prenomParent = $request->get("form")["prenom_parent"];
            $numeroParent = $request->get("form")["numero_parent"];
            $passwordClair = $request->get("form")["password"]["first"];
            $password = $passwordHasher->hashPassword($parentCreer, $passwordClair);
            $emailParent = $request->get("form")["email_parent"];
            $nomEleve = $request->get("form")["nom_eleve"];
            $prenomEleve = $request->get("form")["prenom_eleve"];
            $redoublant = $request->get("form")["is_redoublant"];
            $numeroEleve = $request->get("form")["numero_eleve"];
            $ancienLycee = $request->get("form")["ancien_lycee"];
            $emailEleve = $request->get("form")["email_eleve"];
            $ape = $request->get("form")["ape"];
            $frais = $request->get("form")["frais"];
            $rgpd = $request->get("form")["is_rgpd"];
            $classe = $class;
            $annee = $anne;

            //On enregistre les donnee dans la base de donnee
            $parentCreer->setNom($nomParent)
                ->setPrenom($prenomParent)
                ->setRoles(["ROLE_PARENT"])
                ->setIsparent(true)
                ->setNumero($numeroParent)
                ->setPassword($password)
                ->setEmail($emailParent);

            $em->persist($parentCreer);
            $em->flush();

            $parent = $parentCreer;

            $eleve = new Eleve();
            $eleve->setNom($nomEleve)
                ->setPrenom($prenomEleve)
                ->setIsRedoublant($redoublant)
                ->setNumero($numeroEleve)
                ->setEmail($emailEleve)
                ->setApe($ape)
                ->setFrais($frais)
                ->setAncienLycee($ancienLycee)
                ->setRgpd($rgpd)
                ->setAnnee($annee)
                ->setClasse($classe)
                ->setUser($parent);

            $em->persist($eleve);
            $em->flush();

            $this->addFlash(
                'success',
                "Eleve " . $eleve->getNom() . " " . $eleve->getPrenom() . " a ete Inscript avec success"
            );

            return $this->redirectToRoute('admin_inscription_accueil');
        }

        return $this->render('admin/inscription/inscription.html.twig', [
            'form' => $form->createView(),
            // 'annee' => $annee
        ]);
    }

    /**
     * @Route("/inscription/modifier/{id}", name="editer_inscription")
     */
    public function inscriptionEditer(
        Eleve $eleve,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        TrimestreRepository $trimestreRepository
    ): Response {
        $form = $this->createForm(EditerInscriptionType::class, $eleve);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parentCreer = new User();
            $nomParent = $request->get("editer_inscription")["nom_parent"];
            $prenomParent = $request->get("editer_inscription")["prenom_parent"];
            $numeroParent = $request->get("editer_inscription")["numero_parent"];
            $passwordClair = $request->get("editer_inscription")["password"]["first"];
            $password = $passwordHasher->hashPassword($parentCreer, $passwordClair);
            $emailParent = $request->get("editer_inscription")["email_parent"];
            $nomEleve = $request->get("editer_inscription")["nom"];
            $prenomEleve = $request->get("editer_inscription")["prenom"];
            $redoublant = $request->get("editer_inscription")["isRedoublant"];
            $numeroEleve = $request->get("editer_inscription")["numero"];
            $ancienLycee = $request->get("editer_inscription")["ancienLycee"];
            $emailEleve = $request->get("editer_inscription")["email"];
            $frais = $request->get("editer_inscription")["frais"];
            $rgpd = $request->get("editer_inscription")["rgpd"];
            $idTrimestre = $request->get("editer_inscription")["trimestre"];
            $classe = $eleve->getClasse();
            $annee = $eleve->getAnnee();
            $trimestre = $trimestreRepository->find($idTrimestre);

            $parentCreer->setNom($nomParent)
                ->setPrenom($prenomParent)
                ->setRoles(["ROLE_PARENT"])
                ->setIsparent(true)
                ->setNumero($numeroParent)
                ->setPassword($password)
                ->setEmail($emailParent);

            $em->persist($parentCreer);
            $em->flush();

            $parent = $parentCreer;

            $eleve->setNom($nomEleve)
                ->setPrenom($prenomEleve)
                ->setIsRedoublant($redoublant)
                ->setNumero($numeroEleve)
                ->setEmail($emailEleve)
                ->setFrais($frais)
                ->setAncienLycee($ancienLycee)
                ->setRgpd($rgpd)
                ->setAnnee($annee)
                ->setClasse($classe)
                ->setUser($parent);

            $em->persist($eleve);
            $em->flush();

            $this->addFlash(
                'success',
                "Eleve " . $eleve->getNom() . " " . $eleve->getPrenom() . " a ete Inscript avec success"
            );

            return $this->redirectToRoute('admin_inscription_accueil');
        }

        return $this->render('admin/inscription/editerInscription.html.twig', [
            'form' => $form->createView(),
            'eleve' => $eleve
        ]);
    }
}
