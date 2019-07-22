<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);


        for($i=0;$i<10;$i++){
            $microPost = new MicroPost();
            $microPost->setText('Neki random text' . rand(0,100));
            $microPost->setTime(new \DateTime('2019-03-10'));
            $manager->persist($microPost);
        }
        $manager->flush();
    }
}
