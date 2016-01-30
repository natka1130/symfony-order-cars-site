<?php
/**
 * Created by PhpStorm.
 * User: timsm
 * Date: 25.01.2016
 * Time: 04:21
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ( $this->isGranted('IS_AUTHENTICATED_FULLY') ) return $this->redirectToRoute('index');

        $categories = $this->getDoctrine()->getRepository("AppBundle:Categories")->findAll();
        return $this->render(
            'login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'title' => 'Logowanie',
                'categories' => $categories
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        if ( $this->isGranted('IS_AUTHENTICATED_FULLY') ) return $this->redirectToRoute('index');

        $user = new Users();
        $user->setRoles(['ROLE_USER']);

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class)
            ->add('mail', EmailType::class, ['invalid_message' => 'Email jest nieprawidłowy'])
            ->add('password', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'Hasła muszą się zgadzać'])
            ->add('add', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $this->getDoctrine()->getRepository("AppBundle:Users")->findBy(['username' => $form->get('username')->getData()]);
            if ( empty($users) )
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('form', "Zarejestrowano pomyślnie");
            }
            else
            {
                $this->addFlash('form', "Taki login już istnieje");
            }
        }

        $categories = $this->getDoctrine()->getRepository("AppBundle:Categories")->findAll();
        return $this->render('register.html.twig', ['form' => $form->createView(), 'categories' => $categories, 'title' => 'Rejestracja']);
    }
}