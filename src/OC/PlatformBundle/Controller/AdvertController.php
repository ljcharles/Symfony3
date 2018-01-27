<?php


// src/OC/PlatformBundle/Controller/AdvertController.php


namespace OC\PlatformBundle\Controller;


// N'oubliez pas ce use :

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class AdvertController extends Controller

{

  public function indexAction()

    {

        // On veut avoir l'URL de l'annonce d'id 5.

        $url = $this->get('router')->generate('oc_platform_home', array(), UrlGeneratorInterface::ABSOLUTE_URL);

        // $url vaut « /platform/advert/5 »



        return new Response("L'URL de l'annonce d'id 5 est : ".$url);

    }

  public function viewAction($id)

  {

    return new Response("Affichage de l'annonce d'id : ".$id);

  }

  // On récupère tous les paramètres en arguments de la méthode

    public function viewSlugAction($slug, $year, $format)

    {

        return new Response(

            "On pourrait afficher l'annonce correspondant au

            slug '".$slug."', créée en ".$year." et au format ".$format."."

        );

    }

}
