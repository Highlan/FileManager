<?php

namespace App\DataFixtures;


use App\Service\FileService;
use App\Service\UploadHelper;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use \App\Entity\File as FileEntity;

class FileFixture extends BaseFixture implements DependentFixtureInterface
{
    private $_uploaderHelper;
    private static $files = array('1.jpeg', '2.jpeg', '3.jpeg');


    public function __construct(UploadHelper $uploaderHelper)
    {
        $this->_uploaderHelper = $uploaderHelper;
    }

    protected function loadData(ObjectManager $manager)
    {

        $users = $manager->getRepository('App:User')->findAll();

        foreach ($users as $user){

            $this->createMany(random_int(1, 3), function ($i, $user) {
                $randomImage = $this->faker->randomElement(self::$files);
                $fs = new Filesystem();
                $targetPath = sys_get_temp_dir() . '/' . $randomImage;
                $fs->copy(__DIR__ . '/files/' . $randomImage, $targetPath, true);
                $file = new File($targetPath);

                $file_entity = new FileEntity($user);
                $file_entity->setFormat($file->guessExtension());
                $file_entity->setSize($file->getSize());
                $file_entity->setName($this->_uploaderHelper->UploadFile($file,FileService::USER_FILE_UPLOAD_PATH . $user->getId()));

                return $file_entity;
            }, $user);

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return array(
            UserFixture::class
        );
    }

}
