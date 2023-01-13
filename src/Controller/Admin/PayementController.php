<?php

namespace App\Controller\Admin;

use App\Entity\Eleve;
use App\Entity\Payement;
use App\Form\Payement\creerApeType;
use App\Form\Payement\CreerPayementType;
use App\Form\Payement\EditerPayementType;
use App\Repository\PayementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/admin/payement", name="admin_payement_")
 */
class PayementController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(PayementRepository $payementRepository, CacheInterface $cacheInterface): Response
    {

        return $this->render('admin/payement/index.html.twig', [
            'payements' => $payementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail(Payement $payement): Response
    {

        return $this->render('admin/payement/detail.html.twig', [
            'payement' => $payement,
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $payement = new Payement();

        $form = $this->createForm(CreerPayementType::class, $payement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($payement);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le payement de " . $payement->getFrais() . " a ete fais avec succes par " . $payement->getEleve()->getNom() . " " . $payement->getEleve()->getNom()
            );

            return $this->redirectToRoute('admin_payement_liste');
        }

        return $this->render('admin/payement/creer.html.twig', [
            'form' => $form->createView(),
            'payement' => $payement
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, Payement $payement): Response
    {

        $form = $this->createForm(EditerPayementType::class, $payement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $payement->setEleve($payement->getEleve());

            $entityManager->persist($payement);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes un payement. Le nouveau payement est " . $payement->getFrais()
            );

            return $this->redirectToRoute('admin_payement_liste');
        }

        return $this->render('admin/payement/editer.html.twig', [
            'form' => $form->createView(),
            'payement' => $payement
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, Payement $payement): Response
    {

        $entityManager->remove($payement);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Payement supprimer avec succes"
        );

        return $this->redirectToRoute('admin_payement_liste');
    }
}
