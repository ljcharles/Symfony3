<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccueilController extends Controller
{
    public function indexAction()
    {
      $listAdverts = array(

    array(

      'title'   => 'Recherche développpeur Symfony',

      'id'      => 1,

      'author'  => 'Alexandre',

      'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',

      'date'    => new \Datetime()),

    array(

      'title'   => 'Mission de webmaster',

      'id'      => 2,

      'author'  => 'Hugo',

      'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',

      'date'    => new \Datetime()),

    array(

      'title'   => 'Offre de stage webdesigner',

      'id'      => 3,

      'author'  => 'Mathieu',

      'content' => 'Nous proposons un poste pour webdesigner. Blabla…',

      'date'    => new \Datetime())

  );


  // Et modifiez le 2nd argument pour injecter notre liste

  return $this->render('OCCoreBundle:Accueil:index.html.twig', array(

    'listAdverts' => $listAdverts

  ));
    }

    public function contactAction(Request $request)
    {
      $this->addFlash(
            'notice',
            'La page de contact n\'est pas encore disponible !'
        );
        // $this->addFlash() is equivalent to $request->getSession()->getFlashBag()->add()

        return $this->redirectToRoute('oc_core_homepage');
    }
}
