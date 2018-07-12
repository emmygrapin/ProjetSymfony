<?php
/**
 * Created by PhpStorm.
 * User: egrapin2017
 * Date: 03/07/2018
 * Time: 12:05
 */

namespace App\Controller;


use App\Entity\Ad;
use App\Entity\Category;
use App\Entity\User;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AnnonceController extends Controller
{
    private $adRepository;

    private $userRepository;
    public function __construct(AdRepository $adRepository, UserRepository $userRepository)
    {
       $this->adRepository = $adRepository;

       $this->userRepository =  $userRepository;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/nouvelle-annonce", name="nouvelle_annonce")
     */
    public function creerAnnonce(EntityManagerInterface $em, Request $request)
    {
        $annonce = new Ad();
        //instance du formulaire
        $form = $this->createForm( AdType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            dump($annonce);
            $annonce->setDateCreated(new \DateTime('now'));
            $annonce->setUser($this->getUser());
            $em->persist($annonce);
            $em->flush();
            $this->addFlash('message','Bravo! Votre annonce a été créée');
            return $this->redirectToRoute('home');
        }
        //passer le formulaire à twig
        return $this->render('annonce/creerAnnonce.twig',[
            'form' =>$form->createView()
        ]);
    }
    /**
     * Méthode pour supprimer une annonce par l'id
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("DELETE")
     * @Route ("/supprimer-annonce/{id}",name="supprimer_annonce")
     *
     */
    public function supprimerAnnonce(EntityManagerInterface $em,Request $request, $id)
    {
        // on récupère le token dans le request
            $tokenSubmitted= $request->request->get('_csrf_token');
            //on vérifie que token récupéré est identique à token généré
            if($this->isCsrfTokenValid('delete-item',$tokenSubmitted)) {
                $annonce = $em->getRepository(Ad::class)->find($id);
                $title = null;
                if (isset($annonce)) {
                    $em->remove($annonce);
                    $em->flush();
                    $title = $annonce->getTitle();
                    $this->addFlash('message', 'Bravo! Votre annonce a été supprimée');
                }
            }
        return $this->redirectToRoute('home');
    }

    /**
     * méthode pour visualiser le détail d'une annonce
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/detail-annonce/{id}",name="detail_annonce")
     */
    public function lireDetailAnnonce(EntityManagerInterface $em,$id, Request $request, AdRepository $adRepository)
    {
        $annonce = $adRepository->find($id);

        return $this->render('annonce/detailAnnonce.html.twig',[
            'annonce'=>$annonce,

        ]);

    }


    /**
     * méthode pour afficher une page qui permet de rechercher une annonce

     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recherche", name="recherche")
     */
    public function recherche(EntityManagerInterface $em, AdRepository $adRepository)
    {
        $user = $this->getUser();
        $categories = $em->getRepository(Category::class)->findAll();
        $limitAll = 100;
        $annonces = $adRepository->findByLimit($limitAll);
        if ($this->isGranted('ROLE_USER')) {
            $annoncesFavoris= $em->getRepository(Ad::class)->findByLiker($user);
        } else {
            $annoncesFavoris=null;
        }

        return $this->render('annonce/recherche.html.twig', [
            'categories'=>$categories,
            'annonces'=>$annonces,
            'annoncesFavoris'=>$annoncesFavoris

        ]);
}
    /**
     * méthode pour rechercher une annonce par un mot clé dans le titre
     * @param EntityManagerInterface $em
     * @param Request $query
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recherche-annonce/{page}", name="recherche_annonce")
     */
    public function rechercheAnnonce(EntityManagerInterface $em,Request $request, $page)
    {
        $params = $request->query->all();

        $limit = 10;

        $categories = $em->getRepository(Category::class)->findAll();
        $annoncesTot= $this->adRepository->findBySearch($params,null);
        $annonces = $this->adRepository->findBySearch($params,$page);

        if($this->isGranted('ROLE_USER'))
        {$annoncesFavoris= $this->adRepository->findByLiker($this->getUser());
        }
        else{
            $annoncesFavoris = null;
        }
        $nbAnnonces =count($annoncesTot);
        $nbPages= ceil($nbAnnonces/$limit);

        return $this->render('annonce/rechercheCritere.html.twig', [
            'annoncesFavoris'=>$annoncesFavoris,
            'annonces' => $annonces,
            'categories'=>$categories,
            'nbAnnonces'=>count($annonces),
            'request'=> [
                "category"=> $params['category'],
                "motCle" => $params['motCle'],
                "prixMin" => $params['prixMin'],
                "prixMax" => $params['prixMax'],
            ],
            'pageActuelle'=> $page,
            'nbPages'=>$nbPages
        ]);
    }

    /**
     * Le paramConverter fait le lien entre id et l'entité Ad
     * Vérifie qu'il y ait bien une entité correspondante, sinon erreur 404
     * @IsGranted("ROLE_USER")
     * @Route("/ajouter-favoris/{id}", name="ajouter_favoris")
     */
    public function ajouterFavoris(EntityManagerInterface $em,Ad $ad)
    {
        $user= $this->getUser();
        $ad->addUserLikers($user);
        $em->persist($ad);
        $em->flush();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Retourne la liste des annonces Favoris du user si user est connecté
     * @IsGranted("ROLE_USER")
     * @param EntityManagerInterface $em
     * @Route ("/mes-favoris/", name="mes_favoris")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listerMesFavoris(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $annonces= $em->getRepository(Ad::class)->findByLiker($user);
        return $this->render('annonce/mesFavoris.html.twig',[
            'annonces'=> $annonces
        ]);
    }

    /**
     * Supprime une annonce des favoris si un utilisateur est connecté
     * @param EntityManagerInterface $em
     * @param Ad $ad
     * @IsGranted("ROLE_USER")
     * @Route("/supprimer-favoris/{id}", name="supprimer_favoris")
     */
    public function supprimerFavoris(EntityManagerInterface$em, Ad $ad)
    {
        $user= $this->getUser();
        $ad->removeUserLiker($user);
        $em->persist($ad);
        $em->flush();

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Retourne la liste des annonces créées par un utilisateur si utilisateur connecté
     * @IsGranted("ROLE_USER")
     * @Route("/lister-mes-annonces", name="lister_mes_annonces")
     */
    public function listerMesAnnonces(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $mesAnnonces = $em->getRepository(Ad::class)->findByUser($user);

        return $this->render('annonce/mesAnnonces.html.twig',[
            'annonces'=>$mesAnnonces
        ]);
    }

    /**
     * Formulaire de modification d'annonce par son utilisateur si utilisateur connecté
     * @param EntityManagerInterface $em
     * @param Ad $annonce
     * @Route ("/modifier-annonce/{id}", name ="modifier_annonce")
     */
    public function modifierAnnonce(EntityManagerInterface $em,Request $request, Ad $annonce)
    {
        $form = $this->createForm( AdType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $annonce->setDateCreated(new \DateTime('now'));
            $em->persist($annonce);
            $em->flush();
            $this->addFlash('message', 'Bravo! Votre annonce a été modifiée');

        }
        return $this->render('annonce/modifierAnnonce.html.twig',[
            'form' =>$form->createView()
        ]);
    }

}