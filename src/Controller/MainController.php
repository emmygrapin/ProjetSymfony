<?php
/**
 * Created by PhpStorm.
 * User: egrapin2017
 * Date: 02/07/2018
 * Time: 14:17
 */

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home(){
        $user = $this->getUser();

        return $this->render('main/accueil.html.twig',[
            'user'=>$user
            ]
        );
    }


}