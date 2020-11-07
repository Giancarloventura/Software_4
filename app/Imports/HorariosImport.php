<?php

namespace App\Imports;

use App\Models\Horario;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;
//use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class HorariosImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use Importable, SkipsErrors;

    public function __construct(string $idSemestre, string $codCurso) 
    {
        $this->idSemestre = $idSemestre;
        $this->idCurso = Curso::where('codigo', $codCurso)->first()->id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $row_index = 0;
        $this->errores = new Collection;
        DB::beginTransaction();
        foreach ($rows as $row) 
        {
            $row_index += 1;
            if($row['codigo_del_profesor']==null) continue;
            $usuario = User::where('codigo', $row['codigo_del_profesor'])->first();
            if(!isset($usuario)){
                $usuario = User::create([
                    'codigo' => $row['codigo_del_profesor'],
                    'email' => $row['correo_del_profesor']
                ]);
            };
            $idSem = $this->idSemestre;
            $idCur = $this->idCurso;
            $request = new Request($row->all());
            $validator = Validator::make($request->all(), [
                'horario' => ['required','numeric',
                    Rule::unique('tHorario')->where(function ($query) use($idSem, $idCur) {
                    return $query
                    ->where('idtSemestre', $idSem)
                    ->where('idtCurso',$idCur);
                }),
            ],
        ], [
            'horario.unique' => 'El horario :input ya ha sido registrado en este ciclo. Fila de excel:'.($row_index+1),
        ]);
            //echo collect($validator->errors(),$row_index);
            if($validator->errors()->count()>0){
                $this->errores->push($validator->errors()->get('horario')[0]);
                continue;
            }
            $id = Horario::create([
                'idtSemestre' => $this->idSemestre,
                'idtCurso' => $this->idCurso,
                'horario' => $row['horario'],
            ])->id;
            DB::table('tUsuario_tRol')->insert([
                ['idtUsuario' => $usuario->id, 'idtRol' => 3, 'idtHorario' => $id],
            ]);
            //$usuario->horarios()->attach($id);
        }
        if($this->errores->count()>0){
            DB::rollBack();
        }
        else{
            DB::commit();
        }
    }

    public function errores()
    {
        return $this->errores;
    }
}
