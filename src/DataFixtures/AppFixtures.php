<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private const USERS = [
        [
            'username' => 'DarkoKlisuric',
            'email' => 'darko@darko.hr',
            'password' => 'd',
            'fullName' => 'Darko Klisurić',
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry12345',
            'fullName' => 'Marry Gold',
        ],
        [
            'username' => 'super_admin',
            'email' => 'super_admin@admin.com',
            'password' => 'admin12345',
            'fullName' => 'Micro Admin',
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];
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
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    private function loadMicroPosts(ObjectManager $manager)
    {
        for($i=0;$i<30;$i++){
            $microPost = new MicroPost();
            $microPost->setText(self::POST_TEXT[rand(0 ,count(self::POST_TEXT)-1)]);
            $date = new \DateTime();
            $date->modify('-' . rand(0,10) . ' day');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(
                self::USERS[rand(0 ,count(self::USERS)-1)]['username']
            ));
            $manager->persist($microPost);
        }
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData){
          $user = new User();
          $user->setUsername($userData['username']);
          $user->setFullname($userData['fullName']);
          $user->setEmail($userData['email']);
          $user->setPassword($this->passwordEncoder->encodePassword(
              $user,
              $userData['password']));


          $this->addReference($userData['username'] , $user);

          $manager->persist($user);
        }
        $manager->flush();
    }

}
