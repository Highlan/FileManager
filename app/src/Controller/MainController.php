<?php

namespace App\Controller;


use App\Entity\File;
use App\Form\FileFormType;
use App\Security\FileVoter;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{

    public function list()
    {
        return $this->render('main/files/list/list.html.twig', [
            'files' => $this->getUser()->getFiles()->getValues()
        ]);
    }

    public function new(Request $request, FileService $fileService)
    {
        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['file']->getData();

            $fileService->create($uploadedFile, $this->getUser());

            $this->addFlash('success', 'File uploaded!');
            return $this->redirectToRoute('index');
        }

        return $this->render('main/files/new/new.html.twig', [
            'file_form' => $form->createView()
        ]);
    }

    public function download(File $file, FileService $fileService): BinaryFileResponse
    {
        $this->denyAccessUnlessGranted(FileVoter::DOWNLOAD, $file);

        return $fileService->download($file);
    }

    public function delete(File $file, FileService $fileService)
    {
        $this->denyAccessUnlessGranted(FileVoter::MANAGE, $file);

        $result = $fileService->remove($file);
        if ($result){
            $this->addFlash('success', 'File removed!');
        }
        else{
            $this->addFlash('error', 'Error on removing file!');
        }
        return $this->redirectToRoute('index');
    }

    public function edit(Request $request, FileService $fileService)
    {
        return $fileService->update($request->request->get('pk'), $request->request->get('value'));
    }
}