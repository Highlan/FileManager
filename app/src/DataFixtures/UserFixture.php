<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $_passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->_passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, function ($i) {
            $user = new User();
            $user->setUsername($this->_faker->userName);
            $user->setPassword($this->_passwordEncoder->encodePassword(
                $user,
                'password'
            ));
            return $user;
        });

        $manager->flush();
    }
}
