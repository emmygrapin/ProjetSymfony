<?php

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    /**
     * Rechercher les annonces par date decroissante dans une limite donnée
     * @param $criteria
     * @param $orderBy
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function findByLimit($limit){
        $em =$this->getEntityManager();
        $dql ="SELECT a
                FROM App\Entity\Ad a
                ORDER BY a.dateCreated DESC ";
        $query= $em->createQuery($dql);
        $query->setMaxResults($limit);
       $result =$query->getResult();
       return $result;

    }

    public function findBySearch(Array $params,$page){
        $em = $this->getEntityManager();
        $limit = 10;
        if($page) {
            $offset = ($page * $limit) - $limit;
        }
        else {
            $offset = 0;
        }
        $dql = "SELECT a
                FROM App\Entity\Ad a 
                WHERE a.category = :category
                ";

        if(!empty($params['category'])) {
            $dql.= " and a.title LIKE :motCle";
        }
        if (!empty($params['motCle'])) {
            $dql.= " and a.price >= :prixMin";
        }
        if (!empty($params['prixMin'])) {
            $dql.= " and a.price <= :prixMax";
        }
        $query = $em ->createQuery($dql);
        $query->setParameter('category',$params['category']);

        if(!empty($params['category'])) {
            $query->setParameter('motCle',"%".$params['motCle']."%");
        }
        if (!empty($params['motCle'])) {
            $query->setParameter('prixMin',$params['prixMin']);
        }
        if (!empty($params['prixMin'])) {
            $query->setParameter('prixMax',$params['prixMax']);
        }
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        $result= $query->getResult();
        return $result;
    }


    /**
     * rechercher une annonce si le titre contient par mot clé
     * @return Ad[] Returns an array of Ad Objects
     */
    public function findByMotCle($motCle,$orderBy,$limit,$offset){
        $em =$this->getEntityManager();
        $dql="SELECT a
                FROM App\Entity\Ad a
                WHERE a.title LIKE :motCle";
       $query =$em->createQuery($dql);
       $query->setParameter('motCle',"%$motCle%");
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
       $result= $query->getResult();
       return $result;
    }

    /**
     * Rechercher toutes les annonces dont le prix est inférieur au prix max
     * @param $prixMax
     * @Return Ad[]
     */
    public function findByPrixMax($prixMax,$orderBy,$limit,$offset){
        $em=$this->getEntityManager();
        $dql="SELECT a
                FROM App\Entity\Ad a
                WHERE a.price <= :prixMax";
        $query = $em->createQuery($dql);
        $query->setParameter('prixMax',$prixMax);
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        $result=$query->getResult();
        return $result;
    }

    public function findByPrixMin($prixMin,$orderBy,$limit,$offset){
        $em=$this->getEntityManager();
        $dql="SELECT a
                FROM App\Entity\Ad a
                WHERE a.price >= :prixMin";
        $query = $em->createQuery($dql);
        $query->setParameter('prixMin',$prixMin);
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        $result=$query->getResult();
        return $result;
    }

    public function findByCategory($category,$orderBy,$limit,$offset){
        $em =$this->getEntityManager();
        $dql="SELECT a
                FROM App\Entity\Ad a
                WHERE a.category = :category";
        $query= $em->createQuery($dql);
        $query->setParameter('category',$category);
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        $result=$query->getResult();
        return $result;
    }

    /**
     * Retourne les annonces favoris pour un utilisateur
     * @param User $liker
     * @return mixed
     */
    public function findByLiker(User $liker){
        $em=$this->getEntityManager();
        $dql ="SELECT a
                FROM App\Entity\Ad a 
                INNER JOIN a.userLikers u
                WHERE u = :liker";
        $query = $em->createQuery($dql);
        $query->setParameter('liker', $liker);
        $result = $query->getResult();
        return $result;
    }

    /**
     * Retourne les annonces créées par un utilisateur
     * @param User $user
     * @return mixed
     *
     */
    public function findByUser(User $user){
        $em =$this->getEntityManager();
        $dql ="SELECT a
                FROM App\Entity\Ad a
                WHERE a.user = :user";
        $query = $em->createQuery($dql);
        $query->setParameter('user', $user);
        $result = $query->getResult();
        return $result;

    }
//    /**
//     * @return Ad[] Returns an array of Ad objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
