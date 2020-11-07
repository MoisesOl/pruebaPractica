<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

# extends AbstractController para obtener
# shortcuts como return this->render
class PostsController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage(){

        $datos = (object)[
            "Id"=>'id 1',
            "Titulo"=>'Titulo 1',
            "Descripcion"=>'Descripcion 1',
            "Imagen"=>'images/stock2.jpg'
        ];

        return $this->render('posts/homepage.html.twig',[$datos]);
    }

    /**
     * @Route("/posts/{slug}")
     */
    public function show($slug){
        return $this->render('posts/show.html.twig', [
            'titulo' => ucwords(str_replace('-', ' ', $slug))
        ]);
    }
}