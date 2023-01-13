<?php

namespace App\Controller\User\Professeur;

use App\Entity\Eleve;
use App\Entity\Note;
use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\MatiereRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/user/professeur", name="user_professeur_")
 */
class ProfesseurController extends AbstractController
{

    /**
     * @Route("/listeDesAnneeAcademique", name="annee_liste")
     */
    public function listeAnnee(AnneeRepository $anneeRepository, CacheInterface $cacheInterface): Response
    {
        $annees = $cacheInterface->get("liste_annee", function(ItemInterface $itemInterface) use ($anneeRepository) {
            $itemInterface->expiresAfter(700000);
            return $anneeRepository->findAll([], ["id" => "DESC"]);
        });

        return $this->render('user/professeur/anneeListe.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/listeDeMesClasses/{idAnneeEnvoyer}", name="liste_classe")
     */
    public function listeClasse(
        $idAnneeEnvoyer,
        AnneeRepository $anneeRepository,
        ClasseRepository $classeRepository
    ): Response {
        /**
         * @var User
         */
        $professeur = $this->getUser();
        $idProfesseur = $professeur->getId();

        $annee = $anneeRepository->find($idAnneeEnvoyer);
        $classes = $classeRepository->getClasse($idProfesseur);

        foreach ($classes as $classe) {
            $idClasse = $classe["classe_id"];
            $class = $classeRepository->find($idClasse);
            $idAnnee = $class->getAnnee()->getId();
            if ($idAnnee == $idAnneeEnvoyer) {
                $classses[] = $class;
            }
        }

        return $this->render('user/professeur/listeClasse.html.twig', [
            'annee' => $annee,
            'classses' => $classses
        ]);
    }

    /**
     * @Route("/listeDesEleves/{idClasse}", name="liste_eleves")
     */
    public function listeEleves($idClasse, EleveRepository $eleveRepository, ClasseRepository $classeRepository): Response
    {
        $classe = $classeRepository->find($idClasse);
        $eleves = $eleveRepository->findEleves($idClasse);

        return $this->render('user/professeur/listeEleves.html.twig', [
            'classe' => $classe,
            'eleves' => $eleves,
        ]);
    }

    /**
     * @Route("/ContactDeParent/{idEleve}", name="contact_parent")
     */
    public function ContactParent($idEleve, EleveRepository $eleveRepository, ClasseRepository $classeRepository): Response
    {
        $eleve = $eleveRepository->find($idEleve);

        return $this->render('user/professeur/contactParent.html.twig', [
            'eleve' => $eleve,
        ]);
    }

    /**
     * @Route("/listeDesNotesDesEleves/{idEleve}", name="liste_notes")
     */
    public function listeNotes($idEleve,EleveRepository $eleveRepository ): Response
    {
        $elev = $eleveRepository->find($idEleve);
        $notes = $elev->getNotes();

        return $this->render('user/professeur/listeNotes.html.twig', [
            'eleve' => $elev,
            'notes' => $notes,
        ]);
    }
}
