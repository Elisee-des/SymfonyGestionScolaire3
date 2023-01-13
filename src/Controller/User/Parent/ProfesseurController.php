<?php

namespace App\Controller\User\Parent;

use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/user/parent/professeur", name="user_parent_professeur_")
 */
class ProfesseurController extends AbstractController
{

    /**
     * @Route("/listeDeAnneeAcademique", name="annee_liste")
     */
    public function listeAnnee(AnneeRepository $anneeRepository, CacheInterface $cacheInterface): Response
    {
        $annees = $cacheInterface->get("liste_annee", function(ItemInterface $itemInterface) use ($anneeRepository) {
            $itemInterface->expiresAfter(700000);
            return $anneeRepository->findAll([], ["id" => "DESC"]);
        });

        return $this->render('user/parent/professeur/anneeListe.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/listeDeVosEleves/{id}", name="liste_eleves")
     */
    public function listeEleve(
        $id,
        EleveRepository $eleveRepository,
        AnneeRepository $anneeRepository,
        ClasseRepository $classeRepository, 
        CacheInterface $cacheInterface
    ): Response {
        /**
         * @var User
         */
        $parent = $this->getUser();
        $idParent = $parent->getId();
        $idAnnee = $id;
        $annee = $anneeRepository->find($idAnnee);

        $eleves = $eleveRepository->getEleves($idAnnee, $idParent);

        // $eeleves = $cacheInterface->get("liste_eleve", function(ItemInterface $itemInterface) use ($eleves) {
        //     $itemInterface->expiresAfter(700000);
        //     return  $eleves;
        // });

        foreach ($eleves as $eleve) {
            $idClasse = $eleve["classe_id"];
            $classe = $classeRepository->find($idClasse);
            $classes[] = $classe;
        }


        
        $cclasses = $cacheInterface->get("liste_classe", function(ItemInterface $itemInterface) use ($classes) {
            $itemInterface->expiresAfter(700000);
            return $classes;
        });

        return $this->render('user/parent/professeur/listeEleves.html.twig', [
            'eleves' => $eleves,
            'classes' => $cclasses,
            'annee' => $annee
        ]);
    }

    /**
     * @Route("/voirLesProfesseur/{idClasse}/{idEleve}", name="liste_professeurs")
     */
    public function listeProf($idClasse, $idEleve, ClasseRepository $classeRepository, EleveRepository $eleveRepository, CacheInterface $cacheInterface): Response
    {
        $classe = $classeRepository->find($idClasse);
        $eleve = $eleveRepository->find($idEleve);
        $professeurs = $classe->getUsers();

        // $professeurss = $cacheInterface->get("liste_professeurs", function(ItemInterface $itemInterface) use ($professeurs) {
        //     $itemInterface->expiresAfter(700000);
        //     return $professeurs;
        // });

        return $this->render('user/parent/professeur/listeProfs.html.twig', [
            'professeurs' => $professeurs,
            'eleve' => $eleve
        ]);
    }
}
