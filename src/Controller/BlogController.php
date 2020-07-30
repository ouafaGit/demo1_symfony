<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Articls;
use App\Repository\ArticlsRepository;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticlsRepository $repository)
    {
        // $repository = $this->getDoctrine()->getRepository(Articls::class);
        $articles = $repository->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title'=> "Welcome my freinds!",
            'age' => 31
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create(Request $request) //ObjectManager $manager
    {
        $article = new Articls();

        $form = $this->createFormBuilder($article)
                     ->add('title', TextType::class, [
                        'attr' => [
                            'placeholder' => "Titre d'article",
                            // 'class' => "form-control"
                        ]
                     ])
                     ->add('content', TextareaType::class, [
                        'attr' => [
                            'placeholder' => "Contenu d'article",
                            // 'class' => "form-control"
                        ]
                     ])
                     ->add('image', TextType::class, [
                        'attr' => [
                            'placeholder' => "Image d'article",
                            // 'class' => "form-control"
                        ]
                     ])
                     ->getForm();
        
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView()
        ]);

        
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    // public function show(ArticlsRepository $repository, $id)
    public function show(Articls $article)
    {
        // $repository = $this->getDoctrine()->getRepository(Articls::class);
        // $article = $repository->find($id);
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }
}
