<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Form\Annee\CreerAnneeType;
use App\Form\Annee\EditerAnneeType;
use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/admin/contact", name="admin_")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact")
     */
    public function index(): Response
    {

        return $this->render('admin/contact/index.html.twig', [
            // 'annees' => $annees,
        ]);
    }

    /**
     * @Route("/", name="parent")
     */
    public function contactParent(): Response
    {

        return $this->render('admin/contact/index.html.twig', [
            // 'annees' => $annees,
        ]);
    }
}
