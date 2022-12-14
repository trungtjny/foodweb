<?php

namespace App\Http\Controllers\Admin;

use App\Events\SendMailCreateAccountEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function createMember(Request $request){
        $input = $request->all();
        $input['password'] = rand(111111,999999);
        $user = User::create($input);
        event(new SendMailCreateAccountEvent($input));
        return $user;
    }

    public function delete(Request $request){
        $dele = User::findOrFail($request->id);
        $user = Auth::user();
        // logger($user);
        // if($user->role < $dele->role) {
        //     return responseError([], 'Permission denied', 403);
        // }
        $dele->delete();
        return true;
    }
    public function list(Request $request){
        $dele = User::where('role', '>', 1)->get();
        return $dele;
    }
    public function detail(Request $request){
        $dele = User::where('role', '>', 1)->get();
        return $dele;
    }
    public function update(Request $request){
        $user = User::findOrFail($request->user_id);
        return $user->update($request->all());
    }
}
