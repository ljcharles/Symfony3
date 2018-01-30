<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadProduct.php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Product;

class LoadProduct implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des noms et prix de produit à ajouter
    $namesAndPrices = array(
      'Oeuf' => 1,
      'Poulet' => 15,
      'Saucisse' => 18,
      'Fromage' => 14,
      'Pomme' => 2,
      'Ananas' => 9,
      'Jambon' => 5
    );

    foreach ($namesAndPrices as $nameAndPrix => $nameAndPrix_value) {
      // On crée le produit
      $product = new Product();
      $product->setNom($nameAndPrix);
      $product->setPrix($nameAndPrix_value);

      // On la persiste
      $manager->persist($product);
    }

    // On déclenche l'enregistrement de toutes les produits
    $manager->flush();
  }
}
