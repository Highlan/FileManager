<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\FileFormType;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{

    public function index(UserInterface $user)
    {
        return $this->render('main/file/list.html.twig', [
            'files' => $user->getFiles()->getValues() //todo
        ]);
    }

    public function newAction(Request $request, FileService $fileService, UserInterface $user)
    {
        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['file']->getData();

            $fileService->create($uploadedFile, $user); //todo

            $this->addFlash('success', 'File Uploaded!');
            return $this->redirectToRoute('index');
        }

        return $this->render('main/file/new.html.twig', [
            'file_form' => $form->createView()
        ]);
    }
}