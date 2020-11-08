<?php


namespace App\Controller;


use App\Entity\Posts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

# extends AbstractController para obtener
# shortcuts como return this->render
class PostsController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage(EntityManagerInterface $entityManager){

        $repository = $entityManager->getRepository(Posts::class);
        $posts = $repository->findAll();
        if (!$posts) {
            throw $this->createNotFoundException(sprintf('Oops!'));
        }

        return $this->render('posts/homepage.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route ("/posts/new")
     */
    public function new(EntityManagerInterface $entityManager)
    {
        $post = new Posts();
        $post->setTitulo('Item 3')
            ->setDescripcion('Description 3')
            ->setImagen('https://www.google.com');
        $entityManager->persist($post);
        $entityManager->flush();
        return new Response(sprintf(
            'Well hallo! El nuevo post tiene id: #%d',
            $post->getId()
        ));
    }

    /**
     * @Route("/posts/{slug}")
     */
    public function show($slug){
        return $this->render('posts/show.html.twig', [
            'titulo' => ucwords(str_replace('-', ' ', $slug))
        ]);
    }

    /**
     * @Route ("/login")
     */
    public function login(){
        return $this->render('posts/login.html.twig');
    }
}