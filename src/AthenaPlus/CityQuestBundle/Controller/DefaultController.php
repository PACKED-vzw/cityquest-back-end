<?php

namespace AthenaPlus\CityQuestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CityQuestBundle:Default:index.html.twig', array('name' => $name));
    }






}
