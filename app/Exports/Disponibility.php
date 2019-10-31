<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class Disponibility implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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

    public function headings(): array
    {
        return $this->header;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->mergeCells('A1:M1');
                $event->sheet->getStyle('A1:M1')->getFont()->setSize(15);
                $event->sheet->getRowDimension(1)->setRowHeight(30);
                $event->sheet->getStyle('A1:M1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->mergeCells('A2:M2');
            },
        ];
    }


}
