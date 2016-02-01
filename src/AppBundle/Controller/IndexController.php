<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
	 *displays a list of cars on the main application
     */
    public function indexAction(Request $request)
    {
        return $this->forward("AppBundle:Cars:carList");
    }
}
