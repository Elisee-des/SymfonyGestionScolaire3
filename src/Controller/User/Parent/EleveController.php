<?php

namespace App\Controller\User\Parent;

use App\Entity\Trimestre;
use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\MatiereRepository;
use App\Repository\NoteRepository;
use App\Repository\TrimestreRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/user/parent", name="user_parent_eleve_")
 */
class EleveController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('user/parent/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/listeDeAnneeAcademique", name="annee_liste")
     */
    public function listeAnnee(AnneeRepository $anneeRepository, CacheInterface $cacheInterface): Response
    {
        $annees = $cacheInterface->get("liste_annee", function(ItemInterface $itemInterface) use ($anneeRepository) {
            $itemInterface->expiresAfter(700000);
            return $anneeRepository->findAll([], ["id" => "DESC"]);
        });

        return $this->render('user/parent/anneeListe.html.twig', [
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
        foreach ($eleves as $eleve) {
            $idClasse = $eleve["classe_id"];
            $classe = $classeRepository->find($idClasse);
            $classes[] = $classe;
        }

        $cclasses = $cacheInterface->get("liste_eleve", function(ItemInterface $itemInterface) use ($classes){
            $itemInterface->expiresAfter(700000);
            return $classes;
        });

        return $this->render('user/parent/listeEleves.html.twig', [
            'eleves' => $eleves,
            'classes' => $cclasses,
            'annee' => $annee
        ]);
    }

    /**
     * @Route("/listeDesDeTrimestresScolaire/{idAnnee}", name="liste_trimestres")
     */
    public function listeTrimestre($idAnnee, TrimestreRepository $trimestreRepository, AnneeRepository $anneeRepository, CacheInterface $cacheInterface): Response
    {
        
        
        $annee = $anneeRepository->find($idAnnee);
        $trimestres = $trimestreRepository->findTrimestre($idAnnee);
        $ttrimestres = $cacheInterface->get("liste_trimestres", function(ItemInterface $itemInterface) use ($trimestres){
            $itemInterface->expiresAfter(700000);
            return $trimestres;
        });

        return $this->render('user/parent/listeTrimestres.html.twig', [
            'trimestres' => $ttrimestres,
            'annee' => $annee
        ]);
    }

    /**
     * @Route("/listeDesNotes/{idTrimestre}", name="liste_notes")
     */
    public function listeNotes(
        $idTrimestre,
        TrimestreRepository $trimestreRepository,
        EleveRepository $eleveRepository,
        NoteRepository $noteRepository,
        MatiereRepository $matiereRepository,
        UserRepository $userRepository,
        CacheInterface $cacheInterface
    ): Response {
        /**
         * @var User
         */
        $parent = $this->getUser();
        $idParent = $parent->getId();

        $trimestre = $trimestreRepository->find($idTrimestre);
        $idAnnee = $trimestre->getAnnee()->getId();

        $eleves = $eleveRepository->getEleves($idAnnee, $idParent);
        foreach ($eleves as $eleve) {
            $idEleve = $eleve["id"];
            $note = $noteRepository->getNotesEleve($idTrimestre, $idEleve);
            $notes[] = $note;
            foreach ($note as $matiere) {
                $idMatiere = $matiere["matiere_id"];
                $matiere = $matiereRepository->find($idMatiere);
                $professeur = $userRepository->findProfesseur($idMatiere);
                $professeurs[] = $professeur;
                $matieres[] = $matiere;
            }
        }

        
        $profs = $cacheInterface->get("liste_profs", function(ItemInterface $itemInterface) use ($professeurs){
            $itemInterface->expiresAfter(700000);
            return $professeurs;
        });

        
        $mmatieres = $cacheInterface->get("liste_matiere", function(ItemInterface $itemInterface) use ($matieres){
            $itemInterface->expiresAfter(700000);
            return $matieres;
        });

        $ntss = $notes[0];
        $nnotes = $cacheInterface->get("liste_notes", function(ItemInterface $itemInterface) use ($ntss){
            $itemInterface->expiresAfter(700000);
            return $ntss;
        });

        return $this->render('user/parent/listeNotes.html.twig', [
            'professeurs' => $profs,
            'matieres' => $mmatieres,
            'notes' => $nnotes,
        ]);
    }

    /**
     * @Route("/voirLesProfesseur/{idAnnee}", name="liste_prof")
     */
    public function listeProf(
        $idAnnee,
        ClasseRepository $classeRepository,
        UserRepository $userRepository,
        EleveRepository $eleveRepository
    ): Response {
        // /**
        //  * @var User
        //  */
        // $parent = $this->getUser();
        // $idParent = $parent->getId();
        // $eleves = $eleveRepository->getEleves($idAnnee, $idParent);

        // $classes = $classeRepository->findClasse($idAnnee);


        // foreach ($classes as $classe) {
        //     $idClasse = $classe["id"];
        //     $idProfesseur = $userRepository->findIdProfesseur($idClasse);
        //     $idProfesseurs[] = $idProfesseur;
        // }
        // dd($idProfesseurs);

        return $this->render('user/parent/listeProf.html.twig', [
            // 'classes' => $classes,
        ]);
    }
}
