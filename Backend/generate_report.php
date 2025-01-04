<?php
session_start();
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('db.php');

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$db = new Database();
$conn = $db->getConnection();

// User details
$userQuery = $conn->prepare("SELECT name, id FROM users WHERE id = :userId");
$userQuery->execute([':userId' => $userId]);
$user = $userQuery->fetch();

$fromDate = $_POST['from_date'] ?? date('Y-m-01');
$toDate = $_POST['to_date'] ?? date('Y-m-t');

$expensesQuery = $conn->prepare("
    SELECT e.expense_date AS Date, c.name AS Category, e.amount AS Amount
    FROM expenses e
    JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = :userId AND e.expense_date BETWEEN :fromDate AND :toDate
    ORDER BY e.expense_date ASC
");
$expensesQuery->execute([':userId' => $userId, ':fromDate' => $fromDate, ':toDate' => $toDate]);
$expenses = $expensesQuery->fetchAll();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties and title
$spreadsheet->getProperties()->setCreator('Your Name')->setTitle('Expense Report');
$sheet->setCellValue('A1', 'Expense Report')->mergeCells('A1:C1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

// Headers
$sheet->setCellValue('A2', 'User Name: ' . $user['name']);
$sheet->setCellValue('A3', 'User ID: ' . $user['id']);
$sheet->setCellValue('A4', 'Period: ' . $fromDate . ' to ' . $toDate);
$sheet->fromArray(['Date', 'Category', 'Amount'], NULL, 'A6');
$sheet->getStyle('A6:C6')->getFont()->setBold(true);

// Inserting data and calculating total
$row = 7;
$totalAmount = 0;
foreach ($expenses as $expense) {
    $sheet->fromArray([$expense['Date'], $expense['Category'], $expense['Amount']], NULL, "A$row");
    $totalAmount += $expense['Amount'];
    $row++;
}

// Adding total row
$sheet->setCellValue('A' . $row, 'Total');
$sheet->setCellValue('C' . $row, $totalAmount);
$sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);

// Save Excel 2007 file and output
$writer = new Xlsx($spreadsheet);
$formattedFromDate = date('Ymd', strtotime($fromDate));
$formattedToDate = date('Ymd', strtotime($toDate));
$filename = 'Expense_Report_' . $formattedFromDate . '_to_' . $formattedToDate . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
$writer->save('php://output');
exit;
?>
