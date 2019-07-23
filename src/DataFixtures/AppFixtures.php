<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadMicroPosts($manager);
        $this->loadUsers($manager);

    }

    private function loadMicroPosts(ObjectManager $manager)
    {
        for($i=0;$i<10;$i++){
            $microPost = new MicroPost();
            $microPost->setText('Neki random text' . rand(0,100));
            $microPost->setTime(new \DateTime('2019-03-10'));
            $manager->persist($microPost);
        }
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
      $user = new User();
      $user->setUsername('DarkoKlisuric');
      $user->setFullname('Darko Klisuric');
      $user->setEmail('klisuric@gmail.com');
      $user->setPassword($this->passwordEncoder->encodePassword($user , 'd'));

      $manager->persist($user);
      $manager->flush();
    }

}
