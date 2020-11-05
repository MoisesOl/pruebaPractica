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
        return $this->render('posts/landing.html.twig');
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