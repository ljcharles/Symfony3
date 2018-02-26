<?php

namespace KL\RestaurationBundle\Repository;

/**
*ProduitRepository
*/

class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
  public function findAllOrderedByName()
  {
    return $this->getEntityManager()
        ->createQuery(
          'SELECT p FROM KLRestaurationBundle:Produit p ORDER BY p.name ASC'
          )
        ->getResult();
  }

  public function findArray($array)
  {
    $qb = $this->createQueryBuilder('u')
              ->select('u')
              ->where('u.id in (:array)')
              ->setParameter('array',$array);
    return $qb->getQuery()->getResult();
  }
} ?>
