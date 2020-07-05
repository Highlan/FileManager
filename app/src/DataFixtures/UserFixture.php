<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $_passwordEncoder;
    private static $roles = array(['ROLE_ADMIN'], ['ROLE_USER']);


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->_passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, function ($i) {
            $user = new User();
            $user->setUsername($this->faker->userName);
            $user->setPassword($this->_passwordEncoder->encodePassword($user, 'password'));
            $user->setRoles($this->faker->randomElement(self::$roles));

            return $user;
        });

        $manager->flush();
    }
}
