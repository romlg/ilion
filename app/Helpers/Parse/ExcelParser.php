<?php

namespace App\Helpers\ExcelParser;

class ExcelParser {

    public static function get_array_xlsx($originalFile) {

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($originalFile->getRealPath());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        return $sheetData;
    }

    public static function get_array_xls($originalFile) {

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load($originalFile->getRealPath());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        return $sheetData;
    }
}
