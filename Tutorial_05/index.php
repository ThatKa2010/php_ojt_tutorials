<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Reader</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <?php
        error_reporting(1);
        require 'libs/vendor/autoload.php';

        use PhpOffice\PhpWord\IOFactory as PhpWordIOFactory;
        use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;

        $directory = 'files/';

        $file = 'sample.csv';
        $file1 = 'sample.txt';
        $file2 = 'sample.docx';
        $file3 = 'sample.xlsx';

        $content = file_get_contents($directory . $file1);
        echo "<h2>TEXT File</h2>";
        echo nl2br($content);

        $phpWord = PhpWordIOFactory::load($directory . $file2);
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text .= $element->getText();
            }
        }
        echo "<h2>DOCUMENT File</h2>";
        echo nl2br($text);

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
        $spreadSheet = $reader->load($directory . $file);
        $workSheet = $spreadSheet->getActiveSheet();
        echo "<h2>CSV File</h2>";
        echo "<table>";
        foreach ($workSheet->getRowIterator() as $row) {
            echo "<tr>";
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            foreach ($cellIterator as $col) {
                if ($rowIndex <= 1) {
                    echo "<td style='border-bottom: 1px solid #e3e3e3;'>";
                } else {
                    echo "<td>";
                }
                if ($rowIndex == 0) {
                    echo "<strong>";
                }
                echo $col->getValue();
                if ($rowIndex == 0) {
                    echo "</strong>";
                }
                echo "</td>";
            }
            echo "</tr>";
            $rowIndex++;
        }
        echo "</table>";

        $spreadsheet = SpreadsheetIOFactory::load($directory . $file3);
        $sheet = $spreadsheet->getActiveSheet();
        echo "<h2>EXCEL File</h2>";
        echo "<table>";
        $rowIndex = 0;
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            echo "<tr>";
            foreach ($cellIterator as $cell) {
                if ($rowIndex <= 1) {
                    echo "<td style='border-bottom: 1px solid #e3e3e3;'>";
                } else {
                    echo "<td>";
                }
                if ($rowIndex == 0) {
                    echo "<strong>";
                }
                echo $cell->getValue();
                if ($rowIndex == 0) {
                    echo "</strong>";
                }
                echo "</td>";
            }
            echo "</tr>";
            $rowIndex++;
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>
