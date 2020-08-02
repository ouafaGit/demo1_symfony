<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Articls;
use App\Entity\Categories;
use App\Entity\Comments;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr-FR');
        // create 3 categories
        for ($i=1; $i <= 3; $i++) { 
            $category = new Categories();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
            $manager->persist($category);

            // Create 3 or 4 articles foreach category
            for ($j=1; $j <=mt_rand(4, 6) ; $j++) { 
                $article = new Articls();
                $content = '<p>'.join($faker->paragraphs(5), '</p><p>') .'</p>';
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategories($category);
                $manager->persist($article);

                for ($k=1; $k <= mt_rand(4, 10) ; $k++) { 
                    $comment = new Comments();
                    $content = '<p>'.join($faker->paragraphs(2), '</p><p>') .'</p>';
                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;
                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                            ->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }
        
        $manager->flush();
    }
}
