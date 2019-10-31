<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class SpreadSheetExport implements FromCollection
{

    private $dataSource;
    private $header;

    public function __construct($dataSource, $header)
    {
        $this->dataSource = $dataSource;
        $this->header = $header;
        $this->collection();
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->dataSource;
    }
}
