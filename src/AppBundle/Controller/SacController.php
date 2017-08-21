<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SacController extends Controller
{
    /**
     * @Route("/", name="sac_index")
     */
    public function listAction()
    {
        return $this->render('sac/index.html.twig', []);
    }
}
