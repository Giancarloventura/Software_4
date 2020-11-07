<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsuarioExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('id', 'codigo', 'nombre', 'apellido_paterno', 'apellido_materno')->get();
    }

    public function headings(): array
    {
        return ["id","Codigo","nombre", "Apellido Parterno", "Apellido Materno"];
    }

}
