<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
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
