<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use Datatables;

class DataController extends Controller
{
    public function index()
    {
        $data = Data::get();
        if(request()->ajax()) {
            return datatables()->of($data)->make(true);
        }
        return view('home');
    }
}
