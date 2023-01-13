<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 *
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function add(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNotesEleve($idTrimestre, $idEleve)
    {
        $connexion = $this->_em->getConnection();

        $requete = "SELECT * FROM `note` WHERE trimestre_id=$idTrimestre AND eleve_id=$idEleve";

        $resultat = $connexion->executeQuery($requete);

        $data = $resultat->fetchAllAssociative();

        return $data;
    }

    public function findNote($idEleve, $note)
    {
        $connexion = $this->_em->getConnection();

        $requete = "SELECT * FROM `note` WHERE eleve_id=$idEleve AND note=$note";

        $resultat = $connexion->executeQuery($requete);

        $data = $resultat->fetchAllAssociative();

        return $data;
    }

    public function findNotes($idMatiere)
    {
        $connexion = $this->_em->getConnection();

        $requete = "SELECT * FROM `note` WHERE matiere_id=$idMatiere";

        $resultat = $connexion->executeQuery($requete);

        $data = $resultat->fetchAllAssociative();

        return $data;
    }

    //    /**
    //     * @return Note[] Returns an array of Note objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Note
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
