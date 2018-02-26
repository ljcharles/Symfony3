<?php

namespace KL\RestaurationBundle\Repository;

/**
 * GammeProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GammeProduitRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLikeQueryBuilder($pattern)
    {
       return $this
         ->createQueryBuilder('g')
         ->where('g.nom LIKE :pattern')
         ->setParameter('pattern', $pattern)
       ;
    }

    public function findAllOrderedByName()
    {
      return $this->getEntityManager()
          ->createQuery(
            'SELECT g FROM KLRestaurationBundle:GammeProduit g ORDER BY g.nom ASC'
            )
          ->getResult();
    }
}