<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function api()
    {
        $students = Student::all();
        $datatables = datatables()->of($students)->addIndexColumn();

        return $datatables->make(true);
    }

    public function store(Request $request)
    {
    $rules = [
        'nis' => 'required|min:5',
        'nama' => 'required',
        'email' => 'required|email|unique:students,email',
        'photo' => 'image|mimes:jpeg,png|max:100',
    ];
    $messages = [
        'nis.required' => 'NIS harus diisi.',
        'nis.min' => 'NIS harus memiliki setidaknya :min karakter.',
        'nama.required' => 'Nama harus diisi.',
        'nama.min' => 'Nama harus memiliki setidaknya :min karakter.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Gunakan email yang valid.',
        'email.unique' => 'Email telah digunakan.',
        'photo.image' => 'Gunakan gambar yang valid.',
        'photo.mimes' => 'Format yang diizinkan hanya :mimes.',
        'photo.max' => 'Size gambar maksimal :max.',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
        return redirect('home')
            ->withErrors($validator)
            ->withInput();
    }else{
        Student :: create($request->all());

        return redirect('home');
    }
    }
    public function update(Request $request, Student $student)
    {
    $rules = [
        'nis' => 'required|min:5',
        'nama' => 'required',
        'email' => 'required|email|unique:students,email',
        'photo' => 'image|mimes:jpeg,png|max:100',
    ];
    $messages = [
        'nis.required' => 'NIS harus diisi.',
        'nis.min' => 'NIS harus memiliki setidaknya :min karakter.',
        'nama.required' => 'Nama harus diisi.',
        'nama.min' => 'Nama harus memiliki setidaknya :min karakter.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Gunakan email yang valid.',
        'email.unique' => 'Email telah digunakan.',
        'photo.image' => 'Gunakan gambar yang valid.',
        'photo.mimes' => 'Format yang diizinkan hanya :mimes.',
        'photo.max' => 'Size gambar maksimal :max.',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
        return redirect('home')
            ->withErrors($validator)
            ->withInput();
    }else{
        $student->update($request->all());

        return redirect('home');
    }
    }
    public function destroy(Student $student)
    {
        $student->delete();
    }
}
