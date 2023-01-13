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
 * @Route("/admin/contact", name="admin_contact_")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="acceuil")
     */
    public function index(AnneeRepository $anneeRepository, Request $request): Response
    {

        $annees = $anneeRepository->findBy([], ["id" => "DESC"]);

        if (isset($_POST["continue"])) {
            return $this->redirectToRoute('admin_contact_parents');
        }

        return $this->render('admin/contact/index.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/parents", name="parents")
     */
    public function contactParent(AnneeRepository $anneeRepository, Request $request, UserRepository $userRepository): Response
    {
        $idAnnee = $request->get("annee");

        $annee = $anneeRepository->find($idAnnee);
        
        $parents = $userRepository->;

        return $this->render('admin/contact/contactParents.html.twig', [
            'annee' => $annee,
        ]);
    }
}
