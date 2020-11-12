<?php

namespace App\DataFixtures;

use App\Entity\Posts;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 5 products! Bam!
        for ($i = 1; $i < 6; $i++) {
            $post = new Posts();
            $post->setTitulo(sprintf('Item ',$i));
            $post->setDescripcion(sprintf('Description ',$i));
            $post->setImagen(sprintf('images/stock2.jpg'));
            $manager->persist($post);
        }

        $manager->flush();
    }
}
