<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccueilController extends Controller
{
    public function indexAction()
    {
      // Et modifiez le 2nd argument pour injecter notre liste
      return $this->render('OCCoreBundle:Accueil:index.html.twig');
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
