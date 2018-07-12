<?php
/**
 * Created by PhpStorm.
 * User: egrapin2017
 * Date: 03/07/2018
 * Time: 09:58
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class FooterController
 * @Route("/informations", name="informations_")
 */
class InformationsController extends Controller
{
    /**
     * @Route("/faq", name="faq")
     */
    public function lireFaq(){
        return $this->render('informations/faq.html.twig');
    }
    /**
     * @Route("/cgu", name="cgu")
     */
    public function lireCgu(){
        return $this->render('informations/cgu.html.twig');
    }
}