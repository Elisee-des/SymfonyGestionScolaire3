<?php

namespace App\Controller\Admin;

use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtatController extends AbstractController
{
    /**
     * @Route("/admin/etat", name="admin_etat")
     */
    public function index(Request $request, AnneeRepository $anneeRepository, ClasseRepository $classeRepository): Response
    {
        $annees = $anneeRepository->findAll();

        // if (isset($_POST["ok"])) {
            $idAnnee = $request->get("annee");

            $data = $anneeRepository->getEtatByAnnee($idAnnee);

            $totalTrimestre = $data["etat1"];
            // dd($totalTrimestre);
            $totalEleve = count($data["etat2"]);
            $totalClasse = count($data["etat3"]);

            // return $this->redirectToRoute('admin_etat');
        // }

        return $this->render('admin/etat/index.html.twig', [
            "annees" => $annees,
            // "idAnnee" => $idAnnee,
            "totalTrimestre" => $totalTrimestre,
            // "totalEleve" => $totalEleve,
            // "totalClasse" => $totalClasse,

        ]);
    }
}
