<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends BaseController
{

    public function list(){
        $userId = auth('api')->user()->id;

        $tasks = DB::table('tasks')->where('user_id', $userId)->get();

        return $this->sendResponse($tasks);
    }




    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Erro de Validação.', $validator->errors());
        }

        $input = $request->all();
        $input['user_id'] = auth('api')->user()->id;

        $newTask = Tasks::create($input);

        $result = $newTask;

        return $this->sendResponse($result, 'Task Criada com Sucesso.');
    }




    public function edit(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Erro de Validação.', $validator->errors());
        }

        DB::beginTransaction();

        $update = DB::table('tasks')
            ->where('id', $id)
            ->update([
                'title' => $request['title'],
                'description' => $request['description'],
                'status' => $request['status'],
            ]);

        if ($update != 1) {
            DB::rollBack();
            return $this->sendError('Infelizmento não foi possivel Realizar a Edição.');
        }

        DB::commit();

        $task = DB::table('tasks')->where('id', $id)->get();

        return $this->sendResponse($task, 'Task Alterada com Sucesso.');
    }

    
    public function remove($id){
        $remove = DB::table('tasks')
            ->where('id', $id)
            ->delete();

        if ($remove != 1) {
            DB::rollBack();
            return $this->sendError('Ocorreu um erro!');
        }

        return $this->sendResponse($remove, 'Task Removida Com Sucesso.');
    }
}
