<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audit';


    public static function gerarLog($action, $table, $fields){

        try {
            $log = new Audit();
            $log->id_user = auth()->user()->id;
            $log->action = $action;
            $log->table_name = $table;
            $log->fields_changed = $fields;
            $log->save();

        } catch (Exception $e) {
            throw new Exception("Erro ao Gerar o log");
        }
    }
}
