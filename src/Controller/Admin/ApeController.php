<?php

namespace App\Controller\Admin;

use App\Entity\Eleve;
use App\Form\Payement\EditerApeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/ape", name="admin_ape_")
 */
class ApeController extends AbstractController
{
    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, Eleve $eleve): Response
    {

        $form = $this->createForm(EditerApeType::class, $eleve);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $frais = $request->get('editer_ape')["frais"];
            $eleve->setApe($frais);

            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "APE Payé avec success"
            );

            return $this->redirectToRoute('admin_eleve_detail', ["id" => $eleve->getId()]);
        }

        return $this->render('admin/ape/editer.html.twig', [
            'form' => $form->createView(),
            'eleve' => $eleve
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, Eleve $eleve): Response
    {
        $eleve->setApe(0);
        $entityManager->persist($eleve);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Ape supprimer avec succes"
        );

        return $this->redirectToRoute('admin_eleve_detail', ["id" => $eleve->getId()]);
    }
}
