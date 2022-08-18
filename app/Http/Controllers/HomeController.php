<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public  function importExcel(Request $request)
    {
        $file = $request->file('file');
        $data = Excel::toArray(new UsersImport, $file )[0];
        return redirect()->back()->with('data',$data);
    }
}
