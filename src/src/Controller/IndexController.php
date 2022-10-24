<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\EmployeeFile;
use App\Form\UploadFileType;
use App\Service\EmployeeFileService;
use App\Service\EmployeeService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class IndexController extends AbstractController
{
    private EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    #[Route('/workers-file-upload', name: 'workersFileUpload', methods: ['GET','POST'])]
    public function fileUpload(Request $request, SluggerInterface $slugger): Response
    {
        $file = new EmployeeFile();
        $form = $this->createForm(UploadFileType::class, $file);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $employeeFile = $form->get('document')->getData();
            $newFileName = null;

            if ($employeeFile) {
                $employeeFileService = new EmployeeFileService($slugger);
                $newFileName = $employeeFileService->updateFile($employeeFile);
            }

            if ($newFileName) {
                $this->employeeService->updateEmployeeFromFile($newFileName, EmployeeFile::FILE_PATH);
            }
            return $this->redirectToRoute('app_workers');

        }
        return $this->renderForm('file/index.html.twig', [
            'form' => $form,
        ]);
    }

}
