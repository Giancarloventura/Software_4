<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsuarioImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        var_dump($row);
        return new User([
            'codigo'     => $row['codigo'],
            'nombre'    => $row['nombre'], 
            'apellido_paterno' => $row['apellido_parterno'],
            'apellido_materno' => $row['apellido_materno'],
            'email' => $row['email'],
        ]);
    }
}
