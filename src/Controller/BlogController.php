<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Articls;
use App\Entity\Comments;
use App\Form\ArticleType;
use App\Form\CommentType;
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
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Articls $article = null, Request $request)
    {
        if (!$article) {
            $article = new Articls();
        }
        // $form = $this->createFormBuilder($article)
        //              ->add('title')
        //              ->add('content')
        //              ->add('image')
        //             //  ->add('save', SubmitType::class, [
        //             //     'attr' => [
        //             //         'placeholder' => "Enregistrer",
        //             //     ]
        //             //  ])
        //              ->getForm();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        // dump($article);
        $manager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId() ]);
        }
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode'  => $article->getId() !== null
        ]);

        
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    // public function show(ArticlsRepository $repository, $id)
    public function show(Articls $article, Request $request)
    {
        // $repository = $this->getDoctrine()->getRepository(Articls::class);
        // $article = $repository->find($id);
        $comment = new Comments();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        $manager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid() ) {
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }


        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }
}
