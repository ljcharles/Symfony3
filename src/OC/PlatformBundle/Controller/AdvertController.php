<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
      public function indexAction($page=1)
      {
          // On ne sait pas combien de pages il y a
          // Mais on sait qu'une page doit être supérieure ou égale à 1
          if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            //throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
            return $this->redirectToRoute('oc_platform_default');
          }

          $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
          ;

          $listAdverts = $repository->myFindAll();


          // Et modifiez le 2nd argument pour injecter notre liste

          return $this->render('OCPlatformBundle:Advert:index.html.twig', array(

            'listAdverts' => $listAdverts

          ));
        }

        public function viewAction($id)

        {

          $em = $this->getDoctrine()->getManager();


          // On récupère l'annonce $id

          $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);


          // $advert est donc une instance de OC\PlatformBundle\Entity\Advert

          // ou null si l'id $id  n'existe pas, d'où ce if :

          if (null === $advert) {

             throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
            //return $this->redirectToRoute('oc_platform_default');

          }


          // On récupère la liste des candidatures de cette annonce
          $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert' => $advert))
          ;

          // On récupère maintenant la liste des AdvertSkill
         $listAdvertSkills = $em
           ->getRepository('OCPlatformBundle:AdvertSkill')
           ->findBy(array('advert' => $advert))
         ;

          return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert'           => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills
          ));

        }
        public function addAction(Request $request)
        {
          // On récupère le service

          $antispam = $this->container->get('oc_platform.antispam');

          // Je pars du principe que $text contient le texte d'un message quelconque

          $text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit,
          sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
          Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
          nisi ut aliquip ex ea commodo consequat.';

          if ($antispam->isSpam($text)) {

            throw new \Exception('Votre message a été détecté comme spam !');

          }

          // On récupère l'EntityManager

          $em = $this->getDoctrine()->getManager();

          // Création de l'entité

          $advert = new Advert();

          $advert->setTitle('Recherche développeur Symfony.');

          $advert->setAuthor('Alexandre');

          $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");

          // On peut ne pas définir ni la date ni la publication,
          // car ces attributs sont définis automatiquement dans le constructeur
          // Création de l'entité Image

          $image = new Image();
          $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
          $image->setAlt('Job de rêve');

          // On lie l'image à l'annonce

          $advert->setImage($image);



          // On récupère toutes les compétences possibles

          $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();


          // Pour chaque compétence

          foreach ($listSkills as $skill) {

            // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
            $advertSkill = new AdvertSkill();
            // On la lie à l'annonce, qui est ici toujours la même
            $advertSkill->setAdvert($advert);
            // On la lie à la compétence, qui change ici dans la boucle foreach
            $advertSkill->setSkill($skill);
            // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
            $advertSkill->setLevel('Expert');
            // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
            $em->persist($advertSkill);
          }

          // Création d'une première candidature

          $application1 = new Application();

          $application1->setAuthor('Marine');

          $application1->setContent("J'ai toutes les qualités requises.");


          // Création d'une deuxième candidature par exemple

          $application2 = new Application();

          $application2->setAuthor('Pierre');

          $application2->setContent("Je suis très motivé.");


          // On lie les candidatures à l'annonce

          $application1->setAdvert($advert);

          $application2->setAdvert($advert);


          // Étape 1 : On « persiste » l'entité

          $em->persist($advert);


          // Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est

          // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.

          $em->persist($application1);

          $em->persist($application2);


          // Étape 2 : On « flush » tout ce qui a été persisté avant

          $em->flush();


          // Reste de la méthode qu'on avait déjà écrit

          if ($request->isMethod('POST')) {

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');


            // Puis on redirige vers la page de visualisation de cettte annonce

            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));

          }


          // Si on n'est pas en POST, alors on affiche le formulaire

          return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert' => $advert));

        }

        public function editAction($id, Request $request)
        {
          // Ici, on récupérera l'annonce correspondante à $id
          $em = $this->getDoctrine()->getManager();

          // On récupère l'annonce $id

          $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);


          if (null === $advert) {

            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");

          }

          // La méthode findAll retourne toutes les catégories de la base de données

          $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();


          // On boucle sur les catégories pour les lier à l'annonce

          foreach ($listCategories as $category) {

            $advert->addCategory($category);

          }

          // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
          // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
          // Étape 2 : On déclenche l'enregistrement

          $em->flush();

          // Même mécanisme que pour l'ajout
          if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('oc_platform_view', array('id' => $id));
          }

          return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(

            'advert' => $advert

          ));
        }

        public function deleteAction($id)
        {
          // Ici, on récupérera l'annonce correspondant à $id
          $em = $this->getDoctrine()->getManager();

          // On récupère l'annonce $id
          $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

          if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
          }

          // On boucle sur les catégories de l'annonce pour les supprimer
          foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
          }

          // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
          // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

          // On déclenche la modification
          $em->flush();


          return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(

            'advert' => $advert

          ));
        }

        public function menuAction($limit)
        {

          $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
          ;

          $listAdverts = $repository->myFindAll();

          return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdverts
          ));

        }

        public function editImageAction($advertId)
        {

          $em = $this->getDoctrine()->getManager();

          // On récupère l'annonce
          $advert = $em->getRepository('OCPlatformBundle:Advert')->find($advertId);

          // On modifie l'URL de l'image par exemple
          $advert->getImage()->setUrl('test.png');

          // On n'a pas besoin de persister l'annonce ni l'image.
          // Rappelez-vous, ces entités sont automatiquement persistées car
          // on les a récupérées depuis Doctrine lui-même
          // On déclenche la modification

          $em->flush();
          return new Response('OK');

        }

        public function listAction()
        {
          $listAdverts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->getAdvertWithApplications()
          ;

          foreach ($listAdverts as $advert) {
            // Ne déclenche pas de requête : les candidatures sont déjà chargées !
            // Vous pourriez faire une boucle dessus pour les afficher toutes
            $advert->getApplications();
          }

          // …
        }

      }
