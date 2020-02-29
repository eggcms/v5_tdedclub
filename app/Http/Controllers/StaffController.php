<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function all(Request $request)
    {
        if($request->get('q') != null){
            $query = $request->get('q');
            
            $team = User::where('name','like','%' . $query . '%')
            // ->where('role','!=','zean')
            ->where('active',1)
            ->orWhere('tel','like','%' . $query . '%')
            ->orderBy('role','DESC')
            ->paginate(50);

        }else{
            $team = User::where('active',1)
            // ->where('role','!=','zean')
            ->orderBy('role','ASC')
            ->paginate(50);
        }
        

        return view('staff/all',compact('team'));
    }
}
