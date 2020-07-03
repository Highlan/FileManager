<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, function ($i) {
            $user = new User();
            $user->setUsername($this->faker->userName);
            $user->setPassword('');
            return $user;
        });

        $manager->flush();
    }
}
