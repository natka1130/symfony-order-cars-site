<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CarsController extends Controller
{
    /**
     * @Route("/cars", name="car_list")
     */
    public function carListAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository("AppBundle:Categories")->findAll();
        $cars = $this->getDoctrine()->getRepository("AppBundle:Cars")->findAll();
        $available = [];
        for ( $i = 0; $i < count($cars); $i++ )
        {
            $cars[$i]->setParameters(explode(';', $cars[$i]->getParameters()));

            $lastOrder = $this->getDoctrine()->getRepository("AppBundle:Orders")->findBy(['carId' => $cars[$i]->getId()], ['expDate' => 'DESC'], 1);
            if ( empty($lastOrder) || $lastOrder[0]->getExpDate() < new \DateTime('now') ) $available[$i] = true;
            else $available[$i] = false;
        }

        return $this->render("car_list.html.twig", ['title' => 'Lista samochodów', 'cars' => $cars, 'categories' => $categories, 'route' => 'car_list', 'available' => $available]);
    }

    /**
     * @Route("/cars/cat/{id}", name="car_list_cat")
     */
    public function carListCatAction(Request $request, $id)
    {
        $categories = $this->getDoctrine()->getRepository("AppBundle:Categories")->findAll();
        $category = $this->getDoctrine()->getRepository("AppBundle:Categories")->find($id);
        $cars = $this->getDoctrine()->getRepository("AppBundle:Cars")->findBy(['categoryId' => $id]);
        for ( $i = 0; $i < count($cars); $i++ )
        {
            $cars[$i]->setParameters(explode(';', $cars[$i]->getParameters()));

            $lastOrder = $this->getDoctrine()->getRepository("AppBundle:Orders")->findBy(['carId' => $cars[$i]->getId()], ['expDate' => 'DESC'], 1);
            if ( empty($lastOrder) || $lastOrder[0]->getExpDate() < new \DateTime('now') ) $available[$i] = true;
            else $available[$i] = false;
        }

        return $this->render("car_list.html.twig", ['title' => 'Lista samochodów - kategoria '.$category->getName(), 'cars' => $cars, 'categories' => $categories, 'route' => 'car_list_cat', 'category' => $id, 'available' => $available]);
    }

    /**
     * @Route("/cars/{id}", name="car_desc")
     */
    public function carDescriptionAction(Request $request, $id)
    {
        $categories = $this->getDoctrine()->getRepository("AppBundle:Categories")->findAll();
        $car = $this->getDoctrine()->getRepository("AppBundle:Cars")->find($id);
        $parameters = explode(';', $car->getParameters());
        $category = $this->getDoctrine()->getRepository("AppBundle:Categories")->find($car->getCategoryId());
        $lastOrder = $this->getDoctrine()->getRepository("AppBundle:Orders")->findBy(['carId' => $car->getId()], ['expDate' => 'DESC'], 1);
        $orders = $this->getDoctrine()->getRepository("AppBundle:Orders")->findBy(['carId' => $car]);
        $users = $this->getDoctrine()->getRepository("AppBundle:Users")->findAll();
        if ( empty($lastOrder) || $lastOrder[0]->getExpDate() < new \DateTime('now') ) $available = true;
        else $available = false;

        $order = new Orders();
        $order->setCarId($car->getId());
        $order->setDate(new \DateTime('now'));
        if ( $this->isGranted('IS_AUTHENTICATED_FULLY') ) $order->setUserId($this->getUser()->getId());
        $form = $this->createFormBuilder($order)
            ->add('expDate', \Symfony\Component\Form\Extension\Core\Type\DateType::class, ['label' => 'Do kiedy', 'widget' => 'single_text', 'attr' => ['class' => 'form-control']])
            ->add('add', SubmitType::class, ['label' => 'Potwierdź', 'attr' => ['class' => 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('orderSuccess');
        }

        return $this->render("car_desc.html.twig", ['title' => $car->getName()." - szczegóły", 'rate_title' => "Opinie", 'car' => $car, 'category' => $category, 'categories' => $categories, 'available' => $available, 'parameters' => $parameters, 'form' => $form->createView(), 'rates' => $orders, 'users' => $users]);
    }
}
