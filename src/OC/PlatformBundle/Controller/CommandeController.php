<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\CommandeProduct;
use OC\PlatformBundle\Entity\Commande;

class CommandeController extends Controller
{
  public function indexAction()
  {
    // Ici, on récupérera la liste des commandes, puis on la passera au template

    // Mais pour l'instant, on ne fait qu'appeler le template
    $listCommandes = array(

      array(

        'id'      => 1,

        'adresseLivraison' => 'Rue Georges 1',

        'date'    => new \Datetime()),

        array(

          'id'      => 2,

          'adresseLivraison' => 'Rue Georges 2',

          'date'    => new \Datetime()),

          array(

            'id'      => 3,

            'adresseLivraison' => 'Rue Georges 3',

            'date'    => new \Datetime()),

          );


          // Et modifiez le 2nd argument pour injecter notre liste

          return $this->render('OCPlatformBundle:Commande:index.html.twig', array(

            'listCommandes' => $listCommandes

          ));
        }

        public function seeCommandeAction($id)
        {
          $em = $this->getDoctrine()->getManager();

          // On récupère la commande $id
          $commande = $em->getRepository('OCPlatformBundle:Commande')->find($id);

          // $commande est donc une instance de OC\PlatformBundle\Entity\Commande
          // ou null si l'id $id  n'existe pas, d'où ce if :

          if (null === $commande) {

            throw new NotFoundHttpException("La commande d'id ".$id." n'existe pas.");

          }

          // On récupère maintenant la liste des CommandeProduct
          $listCommandeProduct = $em
          ->getRepository('OCPlatformBundle:CommandeProduct')
          ->findBy(array('commande' => $commande))
          ;

          return $this->render('OCPlatformBundle:Commande:see_commande.html.twig', array(
            'commande'           => $commande,
            'listCommandeProduct' => $listCommandeProduct
          ));
        }


        public function addAction(Request $request)
        {

          // On récupère l'EntityManager

          $em = $this->getDoctrine()->getManager();

          // Création de l'entité
          $commande = new Commande();
          $commande->setAdresseLivraison('Rue Georges 22');

          // On récupère toutes les compétences possibles
          $listeProducts = $em->getRepository('OCPlatformBundle:Product')->findAll();

          // Pour chaque produits
          foreach ($listeProducts as $product) {

            // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
            $commandeProduct = new CommandeProduct();

            // On la lie à la commande, qui est ici toujours la même
            $commandeProduct->setCommande($commande);

            // On la lie au produit, qui change ici dans la boucle foreach
            $commandeProduct->setProduct($product);

            // Arbitrairement, on dit que chaque produits est au nombre de 5
            $commandeProduct->setQuantityProductForOneCommande(5);

            // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
            $em->persist($commandeProduct);

          }

          // Étape 1 : On « persiste » l'entité
          $em->persist($commande);

          // Étape 2 : On « flush » tout ce qui a été persisté avant
          $em->flush();

          if ($request->isMethod('POST')) {

            $request->getSession()->getFlashBag()->add('notice', 'Commande bien enregistrée.');
            // Puis on redirige vers la page de visualisation de cettte commande
            return $this->redirectToRoute('oc_platform_see_commande', array('id' => $commande->getId()));

          }


          // Si on n'est pas en POST, alors on affiche le formulaire
          return $this->render('OCPlatformBundle:Commande:add.html.twig', array('commande' => $commande));

        }


        public function editAction($id, Request $request)
        {
          // Ici, on récupérera la commande correspondante à $id
          $em = $this->getDoctrine()->getManager();

          // On récupère la commande $id
          $commande = $em->getRepository('OCPlatformBundle:Commande')->find($id);

          if (null === $commande) {

            throw new NotFoundHttpException("La commande d'id ".$id." n'existe pas.");

          }

          $em->flush();

          // Même mécanisme que pour l'ajout
          if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Commande bien modifiée.');

            return $this->redirectToRoute('oc_platform_see_commande', array('id' => $id));
          }

          return $this->render('OCPlatformBundle:Commande:edit.html.twig', array(

            'commande' => $commande

          ));
        }


        public function deleteAction($id)
        {
          // Ici, on récupérera la commande correspondant à $id
          $em = $this->getDoctrine()->getManager();

          // On récupère la commande $id
          $commande = $em->getRepository('OCPlatformBundle:Commande')->find($id);

          if (null === $commande) {
            throw new NotFoundHttpException("La commande d'id ".$id." n'existe pas.");
          }

          // On déclenche la modification
          $em->flush();

          return $this->render('OCPlatformBundle:Commande:delete.html.twig', array(

            'commande' => $commande

          ));
        }

      }
