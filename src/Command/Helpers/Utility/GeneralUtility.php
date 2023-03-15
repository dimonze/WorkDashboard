<?php

namespace App\Command\Helpers\Utility;

use App\Entity\Contracts;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\Response;

class GeneralUtility
{
    public static function getWorkingDays(string  $startDate, string $endDate, $holidays = []): int
    {
        $startDate = DateTime::createFromFormat('Y-m-d', $startDate);
        $endDate = DateTime::createFromFormat('Y-m-d', $endDate);

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
        unset($startDate);
        unset($endDate);
        // Возвращаем количество рабочих дней
        return $workingDays;
    }

    public static function getMonthlyBudgets(Contracts $contracts): array
    {
        $startDate = $contracts->getStartDate();
        $endDate = $contracts->getEndDate();
        $monthBudgets = [];

        // Loop through each month between the start and end dates
        $sdate = clone $startDate;
        while ($sdate <= $endDate) {
            $month = $sdate->format('Y-m');

            // Calculate the budget for the current month
            $budgetForMonth = 0;

            foreach ($contracts->getAssignedRoles() as $role) {
                $utilization = $role->getUtilization();
                $price = $role->getRole()->getPrice();
                $roleStartDate = max($role->getStartDate(), $sdate);
                $roleEndDate = $role->getEndDate();
                $daysInMonth = self::getWorkingDays($roleStartDate->format('Y-m-d'),
                    min($roleEndDate->format('Y-m-d'), $sdate->format('Y-m-t')));
                $budgetForMonth += ($utilization * $price * 8 * $daysInMonth);
            }

            // Add the monthly budget to the array
            $monthBudgets[$month] = $budgetForMonth;

            // Move to the next month
            $sdate->modify('+1 month');
        }
        unset($startDate);
        unset($endDate);
        unset($sdate);
        unset($month);
        $responseParameters = KeyValueStore::new([
            'monthBudgets' => $monthBudgets,
        ]);
        return $monthBudgets;
    }

    public static function renderFile(Contracts $contract): Response
    {
        // Загружаем шаблон документа
        $template = new TemplateProcessor(__DIR__ . '/../../../../var/CFA SOW Template FY22-23.docx');

        // Заменяем поля в шаблоне на значения из контракта
        $template->setValue('sowName', str_replace('&', '&amp;', $contract->getName()));
        $template->setValue('startDate', $contract->getStartDate()->format('M d, Y'));
        $template->setValue('endDate', $contract->getEndDate()->format('M d, Y'));
        $template->setValue('sowArea', 'Development');
        $template->setValue('roleName', $contract->getAssignedRoles()[0]->getRole()->getName());
        $template->setValue('rate', $contract->getAssignedRoles()[0]->getRole()->getPrice());
        $template->setValue('utilization', $contract->getAssignedRoles()[0]->getUtilization() * 100);
        $template->setValue('budgetCap', '$' . number_format(($contract->getBudgetCap()), 0, '.', ','));
        // Другие поля

        // Генерируем имя файла для скачивания
        $filename = sprintf('CFA SOW %s.docx', $contract->getName());

        // Возвращаем пользователю готовый файл для скачивания

        $content = $template->save();
        $response = new Response(file_get_contents($content));
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));

        return $response;
    }

    public static function calculateBudget(Contracts $contracts, ObjectManager $em): void
    {
        $results = 0;
        $relatedRoles = $contracts->getAssignedRoles();
        foreach ($relatedRoles as $role) {
            $utilization = $role->getUtilization();

            $price = $role->getRole()->getPrice();

            $workingDays = self::getWorkingDays(
                $role->getStartDate()->format('Y-m-d'),
                $role->getEndDate()->format('Y-m-d'));
            $budget = $workingDays * $utilization * 8 * $price;
            $results += $budget;
        }
        $advPayment = self::setAdvancedPayment($results, $relatedRoles);
        $contracts->setAdvancedPayment($advPayment);
        $contracts->setFte(count($relatedRoles));
        $contracts->setBudgetCap($results);
//        self::updateLedger($contracts);
        $em->persist($contracts);
        $em->flush();
    }

    private static function setAdvancedPayment(float|int $results, \Doctrine\Common\Collections\Collection $relatedRoles)
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

    private static function updateLedger(Contracts $contracts): void
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