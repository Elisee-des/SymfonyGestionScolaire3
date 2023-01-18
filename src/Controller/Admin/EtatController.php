<?php

namespace App\Controller\Admin;

use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtatController extends AbstractController
{
    /**
     * @Route("/admin/etat", name="admin_etat")
     */
    public function index(Request $request, AnneeRepository $anneeRepository, ClasseRepository $classeRepository, UserRepository $userRepository): Response
    {
        $annees = $anneeRepository->findAll();

            $idAnnee = $request->get("annee");

            $data = $anneeRepository->getEtatByAnnee($idAnnee);

            $users = $data["etat6"];

            foreach ($users as $user) {
                
                $idUser = $user["id"];
                $roles = $userRepository->find($idUser)->getRoles();
                if(in_array("ROLE_SURVEILLANT", $roles))
                {
                    $surveillant = $userRepository->find($idUser)->getId();
                    $surveillants[] = $surveillant;
                }

                dd($surveillants);
            }


        return $this->render('admin/etat/index.html.twig', [
            "annees" => $annees,
            // "idAnnee" => $idAnnee,
            "totalEleve" => $data["etat2"][0],
            "totalTrimestre" => $data["etat1"][0],
            "totalClasse" => $data["etat3"][0],
            "totalNote" => $data["etat4"][0],
            "totalAbscence" => $data["etat5"][0],

        ]);
    }
}
