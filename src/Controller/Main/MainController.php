<?php

namespace App\Controller\Main;

use App\Entity\AdministrationSite;
use App\Repository\AdministrationSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(AdministrationSiteRepository $administrationSiteRepository): Response
    {
        $administrationSite = $administrationSiteRepository->findAll();
        return $this->render('main/acceuil.html.twig', [
            'administration' => $administrationSite[0],
        ]);
    }
}
