<?php

namespace App\Repository;

use App\Entity\Classe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Classe>
 *
 * @method Classe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classe[]    findAll()
 * @method Classe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classe::class);
    }

    public function add(Classe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Classe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findClasse($idAnnee)
    {
        $connexion = $this->_em->getConnection();
        $requete = "SELECT * FROM `classe` WHERE annee_id = $idAnnee";
        $resultat = $connexion->executeQuery($requete);
        $data = $resultat->fetchAllAssociative();
        return $data;
    }

    public function getClasse($idProf)
    {
        $connexion = $this->_em->getConnection();
        $requete = "SELECT * FROM `user_classe` WHERE user_id = $idProf";
        $resultat = $connexion->executeQuery($requete);
        $data = $resultat->fetchAllAssociative();
        return $data;
    }

//    /**
//     * @return Classe[] Returns an array of Classe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Classe
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getClasses($idAnnee)
    {

        $connexion = $this->_em->getConnection();

        $resultat = $connexion->executeQuery("SELECT * FROM classe WHERE annee_id=$idAnnee");

        $data = $resultat->fetchAllAssociative();

        return $data;
    }
}
