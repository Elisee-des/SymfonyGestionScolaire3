<?php

namespace App\Controller\Admin;

use App\Entity\AdministrationSite;
use App\Form\Administration\administrationSiteType;
use App\Form\Administration\ContactType;
use App\Form\Administration\description1Type;
use App\Form\Administration\description2Type;
use App\Form\Administration\fonctionnementType;
use App\Form\Administration\localisationType;
use App\Form\Administration\quiSommeNousType;
use App\Form\Administration\sousdescription1Type;
use App\Form\Administration\sousdescription2Type;
use App\Form\Administration\soustitre1Type;
use App\Form\Administration\soustitre2Type;
use App\Form\Administration\soustitre3Type;
use App\Form\Administration\soustitreType;
use App\Form\Administration\titre1Type;
use App\Form\Administration\titre2Type;
use App\Form\Administration\titre3Type;
use App\Form\Administration\titreType;
use App\Repository\AdministrationSiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/administration", name="admin_administration_")
 */
class AdministrationController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil(AdministrationSiteRepository $administrationSiteRepository, Request $request): Response
    {


        return $this->render('admin/administration/index.html.twig');
    }

    /**
     * @Route("/creationDuSite", name="creation")
     */
    public function AdmisistrationSite(Request $request, EntityManagerInterface $em): Response
    {
        $administrationSite = new AdministrationSite();

        $form = $this->createForm(administrationSiteType::class, $administrationSite);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
               'success',
               'Creation avec success des elements du site'
            );

            return $this->redirectToRoute('admin_administration_accueil');

        }

        return $this->render('admin/administration/creation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/titre", name="titre")
     */
    public function titre(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(titreType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le titre du site a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/titre.html.twig');
    }

    /**
     * @Route("/soustitre", name="soustitre")
     */
    public function soustitre(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(soustitreType::class, $administrationSite);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le Soustitre du site a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/soustitre.html.twig');
    }

    /**
     * @Route("/fonctionnement", name="fonctionnement")
     */
    public function fonctionnement(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(fonctionnementType::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le champ de la description du fonctionnement de l'ecole a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/fonctionnement.html.twig');
    }

    /**
     * @Route("/sousfonctionnement", name="sousfonctionnement")
     */
    public function sousfonctionnement(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(sousfonctionnement::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le champ de la description du fonctionnement de l'ecole a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/fonctionnement.html.twig');
    }

    /**
     * @Route("/titre1", name="titre1")
     */
    public function titre1(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(titre1Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le titre 1 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/titre1.html.twig');
    }

    /**
     * @Route("/soustitre1", name="soustitre1")
     */
    public function soustitre1(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(soustitre1Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le soustitre 1 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/soustitre1.html.twig');
    }


    /**
     * @Route("/titre2", name="titre2")
     */
    public function titre2(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(titre2Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le titre 2 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/titre2.html.twig');
    }

    /**
     * @Route("/soustitre2", name="soustitre2")
     */
    public function soustitre2(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(soustitre2Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le soustitre 2 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/soustitre2.html.twig');
    }

    /**
     * @Route("/titre3", name="titre3")
     */
    public function titre3(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(titre3Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le titre 3 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/titre3.html.twig');
    }

    /**
     * @Route("/soustitre3", name="soustitre3")
     */
    public function soustitre3(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(soustitre3Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le soustitre 3 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/soustitre3.html.twig');
    }


    /**
     * @Route("/description1", name="description1")
     */
    public function description1(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(description1Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "La description 1 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/description1.html.twig');
    }

    /**
     * @Route("/sousdescription1", name="sousdescription1")
     */
    public function sousdescription(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(sousdescription1Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le sousdescription 1 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/sousdescription1.html.twig');
    }


    /**
     * @Route("/description2", name="description2")
     */
    public function description2(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(description2Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "La description 2 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/description2.html.twig');
    }

    /**
     * @Route("/sousdescription2", name="sousdescription2")
     */
    public function sousdescription2(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(sousdescription2Type::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "La sousdescription 2 a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/sousdescription2.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ContactType::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Les contacts ont ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/contact.html.twig');
    }


    /**
     * @Route("/localisation", name="localisation")
     */
    public function localisation(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(localisationType::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "La localisation a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/localisation.html.twig');
    }


    /**
     * @Route("/quiSommeNous", name="qui_somme_nous")
     */
    public function quiSommeNous(Request $request, AdministrationSite $administrationSite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(quiSommeNousType::class, $administrationSite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
            $em->persist($administrationSite);
            $em->flush();

            $this->addFlash(
                'success',
                "Le Qui somme nous a ete modifié avec success "
            );

            return $this->redirectToRoute('admin_administration_accueil');
        }

        return $this->render('admin/administration/quiSommeNous.html.twig');
    }
}
