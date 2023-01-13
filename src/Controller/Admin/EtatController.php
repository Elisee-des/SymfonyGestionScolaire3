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

        if (isset($_POST["ok"])) {
            $idAnnee = $request->get("annee");

            dd($idAnnee);
            $data = $anneeRepository->getEtatByAnnee($idAnnee);

        }

        return $this->render('admin/etat/index.html.twig', [
            "annees" => $annees,
            // "idAnnee" => $idAnnee,
            // "trimestres" => $data["etat1"],
            // "eleves" => $data["etat2"],

        ]);
    }
}
