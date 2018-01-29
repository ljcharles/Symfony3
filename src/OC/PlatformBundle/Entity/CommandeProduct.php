<?php
// src/OC/PlatformBundle/Entity/CommandeProduct.php

namespace OC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oc_commande_product")
 */
class CommandeProduct
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="quantityProductForOneCommande", type="integer")
   */
  private $quantityProductForOneCommande;

  /**
   * @ORM\ManyToOne(targetEntity="OC\PlatformBundle\Entity\Commande")
   * @ORM\JoinColumn(nullable=false)
   */
  private $commande;

  /**
   * @ORM\ManyToOne(targetEntity="OC\PlatformBundle\Entity\Product")
   * @ORM\JoinColumn(nullable=false)
   */
  private $product;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantityProductForOneCommande
     *
     * @param integer $quantityProductForOneCommande
     *
     * @return CommandeProduct
     */
    public function setQuantityProductForOneCommande($quantityProductForOneCommande)
    {
        $this->quantityProductForOneCommande = $quantityProductForOneCommande;

        return $this;
    }

    /**
     * Get quantityProductForOneCommande
     *
     * @return integer
     */
    public function getQuantityProductForOneCommande()
    {
        return $this->quantityProductForOneCommande;
    }

    /**
     * Set commande
     *
     * @param \OC\PlatformBundle\Entity\Commande $commande
     *
     * @return CommandeProduct
     */
    public function setCommande(\OC\PlatformBundle\Entity\Commande $commande)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \OC\PlatformBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set product
     *
     * @param \OC\PlatformBundle\Entity\Product $product
     *
     * @return CommandeProduct
     */
    public function setProduct(\OC\PlatformBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \OC\PlatformBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
