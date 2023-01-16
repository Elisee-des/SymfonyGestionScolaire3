<?php

namespace App\Repository;

use App\Entity\Annee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annee>
 *
 * @method Annee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annee[]    findAll()
 * @method Annee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnneeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annee::class);
    }

    public function add(Annee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getEtatByAnnee($idAnnee)
    {
        $connexion = $this->_em->getConnection();

        $requete1 = "SELECT COUNT(trimestre.id) total FROM `trimestre` WHERE trimestre.annee_id=:idAnnee";
        $resultat1 = $connexion->executeQuery($requete1, ["idAnnee" => $idAnnee]);
        $data1 = $resultat1->fetchAllAssociative();

        $requete2 = "SELECT * FROM eleve e WHERE e.annee_id=:idAnnee";
        $resultat2 = $connexion->executeQuery($requete2, ["idAnnee" => $idAnnee]);
        $data2 = $resultat2->fetchAllAssociative();

        $requete3 = "SELECT * FROM classe c WHERE c.annee_id=:idAnnee";
        $resultat3 = $connexion->executeQuery($requete3, ["idAnnee"=>$idAnnee]);
        $data3 = $resultat3->fetchAllAssociative();

        $requete4 = "SELECT * FROM eleve e, note n WHERE e.annee_id=:idAnnee AND e.id = n.eleve_id";
        $resultat4 = $connexion->executeQuery($requete4, ["idAnnee"=>$idAnnee]);
        $data4 = $resultat4->fetchAllAssociative();


        return [
            "etat1" => $data1,
            "etat2" => $data2,
            "etat3"=>$data3,
            "etat4"=>$data4,

        ];
    }


    public function getOneYear()
    {
        $connexion = $this->_em->getConnection();
        $requete1 = "SELECT * FROM annee ORDER BY nom DESC LIMIT 1";
        $resultat1 = $connexion->executeQuery($requete1);
        $data1 = $resultat1->fetchAllAssociative();

        return $data1;
    }

    //    /**
    //     * @return Annee[] Returns an array of Annee objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Annee
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
