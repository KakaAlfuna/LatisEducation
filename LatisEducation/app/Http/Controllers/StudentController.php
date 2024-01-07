<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
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
        
        $student = new Student();
        
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $student->photo = $photoPath;
        }
        $student->lembaga = $request->input('lembaga');
        $student->nis = $request->input('nis');
        $student->nama = $request->input('nama');
        $student->email = $request->input('email');

        $student->save();

        return redirect('home');
    }
    }
    public function update(Request $request, Student $student)
    {
    $rules = [
        'nis' => 'required|min:5',
        'nama' => 'required',
        'email' => 'required|email|unique:students,email,' . $student->id,
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
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    } else {
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $student->photo = $photoPath;
        }

        $student->lembaga = $request->input('lembaga');
        $student->nis = $request->input('nis');
        $student->nama = $request->input('nama');
        $student->email = $request->input('email');

        $student->save();

        return redirect('home');
    }
    }
    public function destroy(Student $student)
    {
        if ($student) {
            $student->delete();
            return redirect('home')->with('success', 'Data siswa berhasil dihapus.');
        }
        
        return redirect('home')->with('error', 'Siswa tidak ditemukan.');
    }
}
