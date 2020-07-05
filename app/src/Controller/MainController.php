<?php

namespace App\Controller;


use App\Form\FileFormType;
use App\Security\UploadHelper;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            $file = $form->getData();

            //todo

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