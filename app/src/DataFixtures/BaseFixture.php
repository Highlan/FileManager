<?php
namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

abstract class BaseFixture extends Fixture
{
    private $_manager;
    protected $_faker;


    abstract protected function loadData(ObjectManager $manager);

    public function load(ObjectManager $manager)
    {
        $this->_manager = $manager;
        $this->_faker = Factory::create();
        $this->loadData($manager);
    }

    protected function createMany(int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = $factory($i);
            if (null === $entity) {
                throw new \LogicException('Did you forget to return the entity object from your callback to BaseFixture::createMany()?');
            }
            $this->_manager->persist($entity);
        }
    }
}