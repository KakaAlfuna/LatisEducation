<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $siswa = new Student;

            $siswa->lembaga = $faker->randomElement(['Latis Education', 'Tutor Indonesia']);
            $siswa->nis = $faker->randomNumber(6);
            $siswa->nama = $faker->name;
            $siswa->email = str_replace(' ', '', $siswa->nama) . '@gmail.com';

            $siswa->save();
        }
    }
}
