<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\Classe;
use App\Entity\Matiere;
use App\Entity\Note;
use App\Entity\Trimestre;
use App\Form\Note\CreerNoteType;
use App\Form\Note\EditerNoteType;
use App\Repository\AnneeRepository;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\MatiereRepository;
use App\Repository\NoteRepository;
use App\Repository\TrimestreRepository;
use App\Service\EnvoieNotesParents;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/admin/note", name="admin_note_")
 */
class NoteController extends AbstractController
{
    /**
     * @Route("/", name="annee")
     */
    public function index(AnneeRepository $anneeRepository, CacheInterface $cacheInterface): Response
    {
        $annees = $cacheInterface->get("note_annee", function (ItemInterface $itemInterface) use ($anneeRepository) {
            $itemInterface->expiresAfter(700000);
            return $anneeRepository->findAll();
        });

        return $this->render('admin/note/index.html.twig', [
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/liste/trimestre/{id}", name="liste_trimestre")
     */
    public function listeTrimestre(Annee $annee): Response
    {
        $trimestres = $annee->getTrimestres();

        return $this->render('admin/note/trimestre/listeTrimestre.html.twig', [
            'trimestres' => $trimestres,
        ]);
    }

    /**
     * @Route("/liste/classe/{id}", name="liste_classe")
     */
    public function listeClasses(Trimestre $trimestre, ClasseRepository $classeRepository): Response
    {
        $idAnnee = $trimestre->getAnnee()->getId();

        // dd($annee->getId());
        $classes = $classeRepository->getClasses($idAnnee);

        return $this->render('admin/note/classe/listeClasse.html.twig', [
            'classes' => $classes,
        ]);
    }

    /**
     * @Route("/liste/matieres/{id}", name="liste_matiere")
     */
    public function listeMatiere(Classe $classe): Response
    {

        $matieres = $classe->getMatieres();

        return $this->render('admin/note/matiere/listeMatieres.html.twig', [
            'matieres' => $matieres,
            'classe' => $classe
        ]);
    }

    /**
     * @Route("/liste/notes/{id}", name="liste_note")
     */
    public function listeNote(Matiere $matiere, Request $request, EleveRepository $eleveRepository): Response
    {

        $notes = $matiere->getNotes();
        $classeNom = $matiere->getClasse()->getNom();
        if (isset($_POST["export"])) {
            $type_fichier = $request->get("file_type");

            $fichier = new Spreadsheet();

            $active_feuille = $fichier->getActiveSheet();

            $active_feuille->setCellValue("A1", "Nom");
            $active_feuille->setCellValue("B1", "Prenom");
            $active_feuille->setCellValue("C1", "Note");
            $active_feuille->setCellValue("D1", "Devoir");
            $active_feuille->setCellValue("E1", "Trimestre");

            $count = 2;

            foreach ($notes as $note) {
                $idEleve = $note->getEleve()->getId();
                $eleveNom = $eleveRepository->find($idEleve)->getNom();
                $elevePrenom = $eleveRepository->find($idEleve)->getPrenom();
                $active_feuille->setCellValue("A" . $count, $eleveNom);
                $active_feuille->setCellValue("B" . $count, $elevePrenom);
                $active_feuille->setCellValue("C" . $count, $note->getNote());
                $active_feuille->setCellValue("D" . $count, $note->getDevoir());
                $active_feuille->setCellValue("E" . $count, $note->getTrimestre());

                $count = $count + 1;
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($fichier, $type_fichier);

            $nom_fichier = "Notes de " . $matiere->getNom() . "_" . $classeNom . '.' . strtolower($type_fichier);

            $writer->save($nom_fichier);

            header('Content-Type: application/x-www-form-urlencoded');

            header('Content-Transfer-Encoding: Binary');

            header("Content-disposition: attachment; filename=\"" . $nom_fichier . "\"");

            readfile($nom_fichier);

            unlink($nom_fichier);

            exit;
        }

        return $this->render('admin/note/note/listeNote.html.twig', [
            'notes' => $notes,
            'matiere' => $matiere
        ]);
    }

    /**
     * @Route("/liste/eleves/envoye/notes/{id}/{idAnnee}", name="envoye_notes_parents")
     */
    public function envoyeDesNotes(
        $id,
        NoteRepository $noteRepository,
        MatiereRepository $matiereRepository,
        EleveRepository $eleveRepository,
        EnvoieNotesParents $envoieNotesParents
    ): Response {
        $notes = $noteRepository->findNotes($id);

        foreach ($notes as $note) {
            $idEleve = $note["eleve_id"];
            $numeroParent = $eleveRepository->find($idEleve)->getNumero();
            $eleveNom = $eleveRepository->find($idEleve)->getNom();
            $elevePrenom = $eleveRepository->find($idEleve)->getPrenom();
            $matiere = $matiereRepository->find($id);
            $devoir = $note["devoir"];

            $not = $note["note"];
            
            $envoieNotesParents->envoyeNotes($eleveNom, $elevePrenom, $not, $devoir, $matiere, $numeroParent);
        }

        $this->addFlash(
           'success',
           'Sms envoyer avec success'
        );

        return $this->redirectToRoute('admin_note_liste', ["id"=>$matiere->getId()]);
    }

    /**
     * @Route("/liste/eleves/{id}", name="liste_eleve")
     */
    public function listeEleves(Classe $classe): Response
    {
        $eleves = $classe->getEleves();

        return $this->render('admin/note/listeEleves.html.twig', [
            'eleves' => $eleves,
        ]);
    }

    /**
     * @Route("/creer", name="creer")
     */
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $note = new Note();

        $form = $this->createForm(CreerNoteType::class, $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($note);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "La note de " . $note->getNote() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_note_liste');
        }

        return $this->render('admin/note/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajout", name="ajout")
     */
    public function ajout(): Response
    {
        $form = $this->createFormBuilder()
            ->add('annee', EntityType::class, [
                'class' => Annee::class,
                'label' => "Choisir l'annee accademique"
            ])
            ->add('trimestre', EntityType::class, [
                'class' => Trimestre::class,
                'label' => "Choisir le trimestre"
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'label' => "Choisir la matiere"
            ])
            ->add('Contituer', SubmitType::class, [])
            ->setAction($this->generateUrl("admin_note_ajout_continuer"))
            ->getForm();

        return $this->render('admin/note/note/ajoutNotes.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajout/continuer", name="ajout_continuer")
     */
    public function ajoutContinuer(Request $request): Response
    {


        return $this->render('admin/note/ajoutContinuer.html.twig', [
            // 'eleves' => $eleves,
        ]);
    }

    /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(EntityManagerInterface $entityManager, Request $request, note $note): Response
    {

        $form = $this->createForm(EditerNoteType::class, $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($note);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Vous avez modifié avec succes une note. La nouvelle note est " . $note->getNote() . " a ete ajouter avec succes"
            );

            return $this->redirectToRoute('admin_note_liste_note', ["id" => $note->getMatiere()->getId()]);
        }

        return $this->render('admin/note/note/editer.html.twig', [
            'form' => $form->createView(),
            'note' => $note
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(EntityManagerInterface $entityManager, note $note): Response
    {

        $entityManager->remove($note);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Note supprimer avec succes"
        );

        return $this->redirectToRoute('admin_note_liste_note', ["id" => $note->getMatiere()->getId()]);
    }
}
