<?php

namespace App\Controller\Admin;

use App\Repository\HistoriqueConnexionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueConnexionController extends AbstractController
{
    /**
     * @Route("/admin/historique/connexion", name="admin_historique_connexion")
     */
    public function index(HistoriqueConnexionRepository $historiqueConnexionRepository): Response
    {

        return $this->render('admin/historique_connexion/index.html.twig', [
            'historiqueConnexions' => $historiqueConnexionRepository->findBy([], ['id'=>'DESC']),
        ]);
    }
}
