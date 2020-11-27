<?php

namespace App\Imports;

use App\Models\Curso;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;
//use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class CursosImport implements ToModel, WithHeadingRow, SkipsOnError, WithCustomCsvSettings
{
    use Importable, SkipsErrors;

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function __construct(string $idUnidadAcademica) 
    {
        $this->idUnidadAcademica = $idUnidadAcademica;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Curso([
            'idtUnidadAcademica' => $this->idUnidadAcademica,
            'codigo' => $row['codigo'],
            'nombre' => $row['nombre'],
            'estado' => "ACT",
        ]);
    }
}
