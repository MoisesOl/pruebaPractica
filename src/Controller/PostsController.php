<?php


namespace App\Controller;


use App\Entity\Posts;
use App\Entity\User;
use App\Repository\PostsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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

        return $this->render('posts/homepage.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/user/landing")
     * @IsGranted("ROLE_USER")
     */
    public function landing(EntityManagerInterface $entityManager, PostsRepository $repository){

        $repository = $entityManager->getRepository(Posts::class);
        $posts = $repository->findAll();

        return $this->render('posts/landing.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route ("/user/upload")
     * @IsGranted("ROLE_USER")
     */
    public function upload(EntityManagerInterface $entityManager, Request $request)
    {
        return $this->render('posts/upload.html.twig');
    }

    /**
     * @Route ("/user/upload/post")
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user)
    {

        #Codigo para descargar la imagen al servidor local y asignarle un tipo de archivo considerando su contenido
        #-----------------------------------------------------------------------------------------------------------
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');
        $destination = $this->getParameter('kernel.project_dir').'/public/images';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        #Dandole un id unico por si se sube la misma foto mas de dos veces
        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );
        #-----------------------------------------------------------------------------------------------------------

        $post = new Posts();
        $titulo = $request->get('titulo');
        $descripcion = $request->get('descripcion');
        $ruta = "images/".$newFilename;


        $post->setTitulo($titulo)
            ->setDescripcion($descripcion)
            ->setImagen($ruta)
            ->setUser($user);

        $entityManager->persist($post);
        $entityManager->flush();

        $repository = $entityManager->getRepository(Posts::class);
        $posts = $repository->findAll();

        return $this->render('posts/landing.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/user/post/{slug}")
     */
    public function show(string $slug, PostsRepository $postsRepository, UserRepository $userRepository){

        $user = $userRepository->findOneBy(['slug'=>$slug]);

        $post = $postsRepository->findBy(['user'=>$user]);

        #Excepción para mostrar pantalla 404
        if (!$user) {
            throw $this->createNotFoundException(sprintf('Oops! No se encontró al usuario'));
        }

        if (!$user) {
            throw $this->createNotFoundException(sprintf('Oops! No se encontraron posts'));
        }

        return $this->render('posts/show.html.twig', [
            'posts' => $post,
            'usuario' => $user,
        ]);
    }
}