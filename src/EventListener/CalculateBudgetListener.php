<?php

namespace App\EventListener;

use App\Command\Helpers\Utility\GeneralUtility;
use App\Entity\Contracts;
use DateTime;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CalculateBudgetListener
{
    private $results = 0;

    public function postPersist(Contracts $entity, PostPersistEventArgs $args)
    {
        $obj = $args->getObjectManager();
        $this->calculateBudget($entity, $obj);
    }

    public function postUpdate(Contracts $entity, PostUpdateEventArgs $args)
    {
        $obj = $args->getObjectManager();
        $this->calculateBudget($entity, $obj);
    }

    private function calculateBudget(Contracts $contracts, ObjectManager $em): void
    {
        $relatedRoles = $contracts->getAssignedRoles();
        foreach ($relatedRoles as $role) {
            $utilization = $role->getUtilization();

            $price = $role->getRole()->getPrice();
            if($role->getStartDate() == null){
                dump($role->getStartDate());
                dump($contracts->getContractID());
                dump($role->getRole()->getName());
                exit();
            }

            $workingDays = $this->getWorkingDays($role->getStartDate(), $role->getEndDate());
            $budget = $workingDays * $utilization * 8 * $price;
            $this->results += $budget;
        }
        $advPayment = $this->setAdvancedPayment($this->results, $relatedRoles);
        $contracts->setAdvancedPayment($advPayment * 100);
        $contracts->setFte(count($relatedRoles));
        $contracts->setBudgetCap($this->results * 100);
        $this->updateLedger($contracts);
        $em->persist($contracts);
        $em->flush();
        unset($contracts);
        unset($em);
        $this->results = 0;

    }

    private function getWorkingDays(\DateTimeInterface $startDate, \DateTimeInterface $endDate, $holidays = [])
    {
        // Инициализируем переменную для подсчета количества рабочих дней
        $workingDays = 0;

        // Перебираем даты в заданном диапазоне и проверяем каждую на рабочий день
        while ($startDate <= $endDate) {
            // Проверяем, является ли текущая дата рабочим днем (не выходной и не праздник)
            if ($startDate->format('N') < 6 && !in_array($startDate->format('Y-m-d'), $holidays)) {
                $workingDays++;
            }

            // Переходим к следующей дате
            $startDate->modify('+1 day');
        }

        // Возвращаем количество рабочих дней
        return $workingDays;
    }

    private function setAdvancedPayment(float|int $results, \Doctrine\Common\Collections\Collection $relatedRoles)
    {
        $rate = 0;
        $utilization = 0;
        if (100000 > $results) {
            return 0;
        } else {
            foreach ($relatedRoles as $role) {
                $rate += $role->getRole()->getPrice();
                $utilization += $role->getUtilization();
            }
            $utilization = $utilization / count($relatedRoles);
            return $rate * 120 * $utilization;
        }
    }

    private function updateLedger(Contracts $contracts): void
    {
        // Загрузка существующего файла Excel
        $spreadsheet = IOFactory::load(__DIR__ . '/../../var/CFA Institute - Documents Ledger2.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();

        // Поиск первой пустой строки в столбце A
        $lastRow = $worksheet->getHighestDataRow('A');
        if ($worksheet->getCell('A' . $lastRow)->getValue() !== '') {
            $lastRow++;
        }

        // Запись данных в первую пустую строку
        $worksheet->setCellValue('A' . $lastRow, $contracts->getContractID());
        $worksheet->setCellValue('B' . $lastRow, $contracts->getName());

    // Сохранение файла Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(__DIR__ . '/../../var/CFA Institute - Documents Ledger2.xlsx');

    }
}