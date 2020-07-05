<?php

namespace App\Controller;


use App\Form\FileFormType;
use App\Service\UploadHelper;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{

    public function index()
    {
        die('index');
    }

    public function newAction(UserInterface $user, Request $request, UploadHelper $uploadHelper, FileService $fileService)
    {
        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['file']->getData();
            if ($uploadedFile) {
                $newFilename = $uploadHelper->uploadFile($uploadedFile);
                dd($newFilename);
//                $fileService->create();
            }

//            $this->$fileService->create($file);

            $this->addFlash('success', 'File Uploaded!');

            return $this->redirectToRoute('index');
        }


        return $this->render('main/file/new.html.twig', [
            'file_form' => $form->createView(),
            'username'  => $user->getUsername()
        ]);
    }
}