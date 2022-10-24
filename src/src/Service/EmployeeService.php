<?php

namespace App\Service;

use App\Entity\Employee;
use App\Entity\EmployeeFilter;
use App\Repository\EmployeeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class EmployeeService
{
    private EmployeeRepository $repository;

    private $entityManager;


    public function __construct(EmployeeRepository $repository,ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->repository = $repository;
    }

    public function updateEmployeeFromFile($fileName, $filePath): void
    {
        $employeeFile = fopen($filePath . '/' . $fileName, 'r');
        $chiefs = [];
        $i = 1;
        while (($employeeData = fgetcsv($employeeFile, 300, ',')) !== false) {
            $employee = new Employee();
            $employee->setName($employeeData[0]);

            if ($employeeData[1]) {
                if (!array_key_exists($employeeData[1], $chiefs)) {
                    $this->entityManager->flush();
                    /** @var Employee $employeeChief */
                    $employeeChief = $this->repository->findOneBy(['name' => $employeeData[1]]);
                    $chiefs[$employeeChief->getName()] = $employeeChief->getId();
                }

                $employeeChiefId = $chiefs[$employeeData[1]];
                $employee->setChiefId($employeeChiefId);
            } else {
                $employee->setChiefId(null);
            }

            $this->entityManager->persist($employee);
            $i++;
            if ($i % 100000 == 0) {
                $this->entityManager->flush();
            }
        }
        fclose($employeeFile);
        $this->entityManager->flush();
    }

    public function findEmployee(EmployeeFilter $filter)
    {
        return $this->repository->findByForm($filter);
    }

    public function findEmployeeByChiefId(EmployeeFilter $filter, int $chiefId)
    {
        return $this->repository->findByChiefId($filter, $chiefId);
    }
}