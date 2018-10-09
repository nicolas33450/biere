<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Unirest;
use AppBundle\Entity\post;
use AppBundle\Entity\formulaire;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $response = unirest\Request::get('http://api.punkapi.com/v2/beers/random');
        return $this->render('default/index.html.twig',['reponse'=>$response->body]);
    }   

     /**
     * @route("/contact",name="contact")
     */
    public function contact(Request $request){
        return $this->render('default/contact.html.twig');
    }

     /**
     * @route("/galerie",name="galerie")
     */
    public function galerie(Request $request){

        $response = unirest\Request::get('http://api.punkapi.com/v2/beers/');
        return $this->render('default/galerie.html.twig',['reponse'=>$response->body]);
    }

     /**
     * @route("/inscription",name="inscription")
     */
    public function inscription(Request $request){
        return $this->render('default/inscription.html.twig');
    }

    /**
     * @route("/article/{id}",name="article",requirements={"id":"\d+"})
     */
    public function articleAction(Request $request, $id){

        $response = unirest\Request::get('http://api.punkapi.com/v2/beers/'.$id);
        return $this->render('default/article.html.twig',['reponse'=>$response->body,'id'=>$id]);
    }

    /**
     * @route("/search",name="search")
     */
    public function searchAction(Request $request){

        $search = $request->query->get('search');
        $response = unirest\Request::get('http://api.punkapi.com/v2/beers?beer_name='.$search);
        return $this->render('default/search.html.twig',['reponse'=>$response->body,'search'=>$search]);
    }

    /**
     * @route("/add",name="add")
     */
    public function addAction(Request $request){

        $nom = $request->query->get('nom');
        $prenom = $request->query->get('prenom');
        $adresse = $request->query->get('adresse');
        $cp = $request->query->get('cp');
        $ville = $request->query->get('ville');
        $email = $request->query->get('email');

        $formulaire = new Formulaire();

        $formulaire->setNom($nom);
        $formulaire->setPrenom($prenom);
        $formulaire->setAdresse($adresse);
        $formulaire->setCp($cp);
        $formulaire->setVille($ville);
        $formulaire->setEmail($email);

        //entityManager
        $em = $this->getDoctrine()->getManager();

        //on envoie dans la bdd
        $em->persist($formulaire);
        //on valide et on enleve de EntityManager
        $em->flush();

        return $this->render('default/inscription.html.twig');

    }

    /**
     * @route("/listeClient",name="listeClient")
     */
    public function listeClient(Request $request){
        $em = $this->getDoctrine()->getManager();
        $listeClient = $em->getRepository("AppBundle:formulaire")->findAll();
        
        return $this->render('default/listeClient.html.twig',["liste"=>$listeClient]);
    }
    
    /**
     * @route("/modifClient/{id}",name="modifClient",requirements={"id":"\d+"})
     */
    public function modifClient(Request $request,$id){
            
            {
                $em = $this->getDoctrine()->getManager();
                $client = $em->getRepository("AppBundle:formulaire")->find($id);

                if(null !==($request->request->get('nom')))
                { 
                    $nom = $request->request->get('nom');
                    $prenom = $request->request->get('prenom');
                    $adresse = $request->request->get('adresse');
                    $cp = $request->request->get('cp');
                    $ville = $request->request->get('ville');
                    $email = $request->request->get('email');            
                            
                    $client->setNom($nom);
                    $client->setPrenom($prenom);
                    $client->setAdresse($adresse);
                    $client->setCp($cp);
                    $client->setVille($ville);
                    $client->setEmail($email);               
                
                    //on valide et on enleve de EntityManager
                    $em->flush();

                    return $this->redirectToRoute('listeClient');
            }
        return $this->render('default/modifClient.html.twig',["client"=>$client]);

        }
    }

    /**
     * @route("/supClient/{id}",name="supClient",requirements={"id":"\d+"})
     */
    public function supClient(Request $request,$id){
            
       
            $em = $this->getDoctrine()->getManager();
            $client = $em->getRepository("AppBundle:formulaire")->find($id);

                      
            $em->remove($client);
             //on valide et on enleve de EntityManager
             $em->flush();                
       
             return $this->redirectToRoute('listeClient');

    }
}

    
 