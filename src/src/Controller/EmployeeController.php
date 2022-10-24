<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\EmployeeFilter;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use App\Service\EmployeeService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    private EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    #[Route('/employee', name: 'app_workers', methods: ["GET", "POST"])]
    public function index(Request $request): Response
    {
        $page = (int)$request->get('page');
        if ($page < 1) {
            $page = 1;
        }

        $filter = new EmployeeFilter();
        $filter->setPage($page);
        $form = $this->createForm(EmployeeType::class, $filter);
        $form->handleRequest($request);

        $employees = $this->employeeService->findEmployee($form->getData());

        return $this->renderForm('workers/index.html.twig', [
            'form' => $form,
            'employees' => $employees,
            'page' => $page,
        ]);
    }

    #[Route('/employee/{id}', name: 'employee_show', methods: ["GET", "POST"])]
    public function showEmployee(Request $request, Employee $employee): Response
    {
        $page = (int)$request->get('page');
        if ($page < 1) {
            $page = 1;
        }

        $filter = new EmployeeFilter();
        $filter->setPage($page);
        $form = $this->createForm(EmployeeType::class, $filter);
        $form->handleRequest($request);

        $employees = $this->employeeService->findEmployeeByChiefId($form->getData(), $employee->getId());

        return $this->renderForm('workers/byCheifId.html.twig', [
            'employeeName' => $employee->getName(),
            'employeeId' => $employee->getId(),
            'form' => $form,
            'employees' => $employees,
            'page' => $page,
        ]);
    }
}
