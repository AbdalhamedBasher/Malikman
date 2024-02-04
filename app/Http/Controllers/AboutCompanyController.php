<?php

namespace App\Http\Controllers;

use App\Models\AboutCompany;
use Illuminate\Http\Request;

class AboutCompanyController extends Controller
{
    //
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index(){
        $about_company = AboutCompany::first(); // or however you want to get the company data

        return view('about_company',['about_company' => $about_company]);
    }
    //store or update
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required',
        'phone' => 'required',
        'email' => 'required',
        'about' => 'required',
    ]);

    $about_company=AboutCompany::updateOrCreate(
        ['id' => 1], // Column / value pairs to find
        $validatedData // Data to update or create with
    );
    if($about_company){
        return redirect()->back()->with('success','تمت الاضافة بنجاح');
        }
        else{
            return redirect()->back()->with('error','حدث خطأ ما');
        }


}



  
  
   
}
