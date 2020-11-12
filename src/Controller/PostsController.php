<?php


namespace App\Controller;


use App\Entity\Posts;
use App\Repository\PostsRepository;
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

        #Excepción para mostrar pantalla 404
        if (!$posts) {
            throw $this->createNotFoundException(sprintf('Oops!'));
        }

        return $this->render('posts/homepage.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/user/post/{slug}")
     */
    public function show($slug){
        return $this->render('posts/show.html.twig', [
            'usuario' => ucwords(str_replace('-', ' ', $slug))
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
    public function new(Request $request, EntityManagerInterface $entityManager)
    {

        #Codigo para descargar la imagen al servidor local y asignarle un tipo de archivo considerando su contenido
        #-----------------------------------------------------------------------------------------------------------
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');
        $destination = $this->getParameter('kernel.project_dir').'/public/images';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
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
            ->setImagen($ruta);

        $repository = $entityManager->getRepository(Posts::class);
        $posts = $repository->findAll();

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->render('posts/homepage.html.twig', [
            'posts' => $posts
        ]);
    }
}