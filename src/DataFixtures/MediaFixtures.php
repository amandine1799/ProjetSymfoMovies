<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Media;

class MediaFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i =1; $i <= 10; $i++){
          $media = new Media();
          $media->setTitle("Film n°$i")
                 ->setSynopsis("<p>Synopsis n°$i</p>")
                 ->setPoster("http://placehold.it/150x350")
                 ->setReleasedYear("$i");

          $manager->persist($media);
        }

        $manager->flush();
    }
}
