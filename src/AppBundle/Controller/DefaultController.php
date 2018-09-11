<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Unirest;

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

}
