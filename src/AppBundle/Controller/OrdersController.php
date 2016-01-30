<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class OrdersController extends Controller
{
    /**
     * @Route("/orders", name="orders")
     */
    public function ordersAction(Request $request)
    {
        $myOrders = $this->getDoctrine()->getRepository("AppBundle:Orders")->findBy(['userId' => $this->getUser()->getId()], ['date' => 'DESC']);
        $categories = $this->getDoctrine()->getRepository("AppBundle:Categories")->findAll();
        $cars = [];
        for( $i = 0; $i < count($myOrders); $i++ )
        {
            $cars[$i] = $this->getDoctrine()->getRepository("AppBundle:Cars")->findOneBy(['id' => $myOrders[$i]->getCarId()]);
            $myOrders[$i]->setDate($myOrders[$i]->getDate()->format("d.m.Y"));
            $myOrders[$i]->setExpDate($myOrders[$i]->getExpDate()->format("d.m.Y"));
        }
        return $this->render('order_list.html.twig', ['title' => 'Zamówienia', 'orders' => $myOrders, 'categories' => $categories, 'cars' => $cars, 'route' => 'orders']);
    }

    /**
     * @Route("/order-success", name="orderSuccess")
     */
    public function orderSuccessAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository("AppBundle:Categories")->findAll();

        return $this->render('order_success.html.twig', ['title' => 'Zamówienie przyjęte', 'categories' => $categories]);
    }

    /**
     * @Route("/order-rate/{id}", name="orderRate")
     */
    public function orderDeleteAction(Request $request, $id)
    {
        $categories = $this->getDoctrine()->getRepository("AppBundle:Categories")->findAll();
        $order = $this->getDoctrine()->getRepository("AppBundle:Orders")->find($id);
        if( $this->isGranted('IS_AUTHENTICATED_FULLY') && $order->getUserId() === $this->getUser()->getId() )
        {
            $order->setRate(5);
            $form = $this->createFormBuilder($order)
                ->add('rate', IntegerType::class, ['attr' => ['min' => 1, 'max' => 10]])
                ->add('opinion', TextareaType::class)
                ->add('add', SubmitType::class)
                ->getForm();

            $form->handleRequest($request);

            if ( $form->isSubmitted() && $form->isValid() )
            {
                $em = $this->getDoctrine()->getManager();
                $em->flush($order);
                return $this->redirectToRoute('orders');
            }
        }

        return $this->render('order_rate.html.twig', ['title' => 'Ocena samochodu', 'categories' => $categories, 'form' => $form->createView()]);
    }
}
