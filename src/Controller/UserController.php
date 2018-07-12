<?php
/**
 * Created by PhpStorm.
 * User: egrapin2017
 * Date: 06/07/2018
 * Time: 11:08
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends Controller
{
    /**
     * @Route("/inscription", name="user_inscription")
     */
    public function inscription(Request $request,UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $form =$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setDateRegistered(new \DateTime('now'));
            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('message','Bravo! Votre inscription est valide');
            return $this->redirectToRoute('home');
        }
        return $this->render('main/inscription.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}




