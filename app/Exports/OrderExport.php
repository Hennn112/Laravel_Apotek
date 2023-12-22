<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // func collection : proses pengambilan data yang akan ditampilkan di excel
    public function collection()
    {
        return Order::with('user')->get();
    }

    // headings: nama nama th dari file excel
    public function headings(){
        return [
            "Nama ", "Obat", "Total Bayar", "Kasir", "Tanggal"  
        ];
    }
    // map: data yang akan dimunculkan di excelnya (sama kaya foreach di blade)
    public function map($item):array{
        $dataObat = '';
        foreach($item->medicines as $value){
            // ubah object /array data dari column medicines nya menjadi string dengan hasil : Vitamin A (qty 2 : Rp. 18.000)
            $format = $value["name_medicine"] . " (qty " . $value['qty'] . ": Rp. " . number_format($value['sub_price']) . "),";
            $dataObat .= $format;
        }
        return[
            $item->name_customer,
            $dataObat,
            $item->total_price,
            $item->user->name,
            \Carbon\Carbon::parse($item->created_at)->isoFormat($item->created_at),
        ];
    }
}
