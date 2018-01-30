<?php

namespace OC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="oc_commande")
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="ListeProduits", type="array")
     */
    private $listeProduits;

    /**
     * @var string
     *
     * @ORM\Column(name="AdresseLivraison", type="string", length=255)
     */
    private $adresseLivraison;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="datetime")
     */
    private $date;

    public function __construct()

      {

        // Par défaut, la date de l'annonce est la date d'aujourd'hui

        $this->date = new \Datetime();

      }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set listeProduits
     *
     * @param array $listeProduits
     *
     * @return Commande
     */
    public function setListeProduits($listeProduits)
    {
        $this->listeProduits = $listeProduits;

        return $this;
    }

    /**
     * Get listeProduits
     *
     * @return array
     */
    public function getListeProduits()
    {
        return $this->listeProduits;
    }

    /**
     * Set adresseLivraison
     *
     * @param string $adresseLivraison
     *
     * @return Commande
     */
    public function setAdresseLivraison($adresseLivraison)
    {
        $this->adresseLivraison = $adresseLivraison;

        return $this;
    }

    /**
     * Get adresseLivraison
     *
     * @return string
     */
    public function getAdresseLivraison()
    {
        return $this->adresseLivraison;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get prixTotal
     *
     * @return int
     */
    public function getPrixTotal()
    {

      $prix = 0;

      foreach($this->getListeProduits() as $produit) {

        $prix += $produit->getPrix();

      }

      return $prix;

    }
}
