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
    public function findAllAds($page){
        $em =$this->getEntityManager();
        $limit = 10;
        if($page){
            $offset =($page * $limit) - $limit;
        }
        else {
            $offset = 0;
        }

        $dql ="SELECT a
                FROM App\Entity\Ad a
                ORDER BY a.dateCreated DESC ";

        $query= $em->createQuery($dql);
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        $result =$query->getResult();

       return $result;

    }

    /**
     * @param array $params
     * @param $page
     * @return mixed
     */
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
                WHERE 1=1";

        if(!empty($params['category'])) {
            $dql.=" and a.category = :category";
        }
        if(!empty($params['motCle'])) {
            $dql.=" and a.title LIKE :motCle";
        }
        if (!empty($params['prixMin'])) {
            $dql.= " and a.price >= :prixMin";
        }
        if (!empty($params['prixMax'])) {
            $dql.= " and a.price <= :prixMax";
        }
        $query = $em ->createQuery($dql);


        if(!empty($params['category'])) {
            $query->setParameter('category',$params['category']);
        }
        if(!empty($params['motCle'])) {
            $query->setParameter('motCle', '%'.$params['motCle'].'%');
        }
        if (!empty($params['prixMin'])) {
            $query->setParameter('prixMin',$params['prixMin']);
        }
        if (!empty($params['prixMax'])) {
            $query->setParameter('prixMax',$params['prixMax']);
        }
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        $result= $query->getResult();
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

}
