<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class AuthController extends BaseController
{

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Erro de Validação.', $validator->errors());
        }

        $input = $request->all();
        $user = User::create($input);

        $success['user'] = $user;

        return $this->sendResponse($success, 'Usuário Criado com Sucesso.');
    }

    public function login(){
        $credentials = request(['email', 'password']);

        if(! $token = auth('api')->attempt($credentials)){
            return $this->sendError('Sem Autorização', ['error' => 'Unathorized']);
        }

        $success = $this->respondWithToken($token);

        return $this->sendResponse($success, 'Usuário Logado com Sucesso.');

    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

}
