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

        $requete = "SELECT COUNT(trimestre.id) total FROM `trimestre` WHERE trimestre.annee_id=:idAnnee";
        $resultat = $connexion->executeQuery($requete, ["idAnnee" => $idAnnee]);
        $data = $resultat->fetchAllAssociative();


        $requete2 = "SELECT COUNT(e.id) total FROM eleve e WHERE e.annee_id=:idAnnee";
        $resultat2 = $connexion->executeQuery($requete2, ["idAnnee" => $idAnnee]);
        $data2 = $resultat2->fetchAllAssociative();

        $requete3 = "SELECT COUNT(c.id) total FROM classe c WHERE c.annee_id=:idAnnee";
        $resultat3 = $connexion->executeQuery($requete3, ["idAnnee"=>$idAnnee]);
        $data3 = $resultat3->fetchAllAssociative();

        $requete4 = "SELECT COUNT(n.id) total FROM eleve e, note n WHERE e.annee_id=:idAnnee AND n.eleve_id=e.id";
        $resultat4 = $connexion->executeQuery($requete4, ["idAnnee"=>$idAnnee]);
        $data4 = $resultat4->fetchAllAssociative();

        $requete5 = "SELECT COUNT(a.id) total FROM eleve e, abscence a WHERE e.annee_id=:idAnnee AND a.eleve_id=e.id";
        $resultat5 = $connexion->executeQuery($requete5, ["idAnnee"=>$idAnnee]);
        $data5 = $resultat5->fetchAllAssociative();

        


        return [
            "etat1" => $data,
            "etat2" => $data2,
            "etat3"=> $data3,
            "etat4"=> $data4,
            "etat5"=> $data5,

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
