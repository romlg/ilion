<?php

namespace App\Helpers\ExcelParser;

use PhpOffice\PhpSpreadsheet\Reader\Xls;

class ExcelParser {

    public static function get_array($originalFile) {

        $reader = new Xls();
        $spreadsheet = $reader->load($originalFile->getRealPath());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        return $sheetData;
    }
}
