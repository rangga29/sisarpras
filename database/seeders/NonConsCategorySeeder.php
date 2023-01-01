<?php

namespace Database\Seeders;

use App\Models\NonConsCategory;
use Illuminate\Database\Seeder;

class NonConsCategorySeeder extends Seeder
{
    public function run()
    {
        //1
        NonConsCategory::create([
            'category_code' => 'A.01',
            'category_name' => 'Tanah',
            'category_slug' => 'tanah'
        ]);

        //2
        NonConsCategory::create([
            'category_code' => 'A.02',
            'category_name' => 'Bangunan',
            'category_slug' => 'bangunan'
        ]);

        //3
        NonConsCategory::create([
            'category_code' => 'B.01',
            'category_name' => 'Kendaraan',
            'category_slug' => 'kendaraan'
        ]);

        //4
        NonConsCategory::create([
            'category_code' => 'B.02',
            'category_name' => 'Alat Pengangkut',
            'category_slug' => 'alat-pengangkut'
        ]);

        //5
        NonConsCategory::create([
            'category_code' => 'C.01',
            'category_name' => 'Mesin Tulis TIK',
            'category_slug' => 'mesin-tulis-tik'
        ]);

        //6
        NonConsCategory::create([
            'category_code' => 'C.02',
            'category_name' => 'Komputer',
            'category_slug' => 'komputer'
        ]);

        //7
        NonConsCategory::create([
            'category_code' => 'C.03',
            'category_name' => 'Mesin Kalkulasi',
            'category_slug' => 'mesin-kalkulasi'
        ]);

        //8
        NonConsCategory::create([
            'category_code' => 'C.04',
            'category_name' => 'Mesin Reproduksi',
            'category_slug' => 'mesin-reproduksi'
        ]);

        //9
        NonConsCategory::create([
            'category_code' => 'C.05',
            'category_name' => 'Alat Komunikasi',
            'category_slug' => 'alat-kompunikasi'
        ]);

        //10
        NonConsCategory::create([
            'category_code' => 'C.06',
            'category_name' => 'Alat Audio Visual',
            'category_slug' => 'alat-audio-visual'
        ]);

        //11
        NonConsCategory::create([
            'category_code' => 'C.07',
            'category_name' => 'Penyimpanan',
            'category_slug' => 'penyimpanan'
        ]);

        //12
        NonConsCategory::create([
            'category_code' => 'C.08',
            'category_name' => 'Perabot Ruangan',
            'category_slug' => 'perabot-ruangan'
        ]);

        //13
        NonConsCategory::create([
            'category_code' => 'D.01',
            'category_name' => 'Pertukangan',
            'category_slug' => 'pertukangan'
        ]);

        //14
        NonConsCategory::create([
            'category_code' => 'D.02',
            'category_name' => 'Kebersihan',
            'category_slug' => 'kebersihan'
        ]);

        //15
        NonConsCategory::create([
            'category_code' => 'D.03',
            'category_name' => 'Pengamanan',
            'category_slug' => 'pengamanan'
        ]);

        //16
        NonConsCategory::create([
            'category_code' => 'E.01',
            'category_name' => 'Alat Olahraga Umum',
            'category_slug' => 'alat-olahraga-umum'
        ]);

        //17
        NonConsCategory::create([
            'category_code' => 'E.02',
            'category_name' => 'Atletik',
            'category_slug' => 'atletik'
        ]);

        //18
        NonConsCategory::create([
            'category_code' => 'E.03',
            'category_name' => 'Senam',
            'category_slug' => 'senam'
        ]);

        //19
        NonConsCategory::create([
            'category_code' => 'E.04',
            'category_name' => 'Permainan',
            'category_slug' => 'permainan'
        ]);

        //20
        NonConsCategory::create([
            'category_code' => 'F.01',
            'category_name' => 'Alat Kesenian Tradisional',
            'category_slug' => 'alat-kesenian-tradisional'
        ]);

        //21
        NonConsCategory::create([
            'category_code' => 'F.02',
            'category_name' => 'Alat Kesenian Modern',
            'category_slug' => 'alat-kesenian-modern'
        ]);

        //22
        NonConsCategory::create([
            'category_code' => 'G.01',
            'category_name' => 'Fisika',
            'category_slug' => 'fisika'
        ]);

        //23
        NonConsCategory::create([
            'category_code' => 'G.02',
            'category_name' => 'Kimia',
            'category_slug' => 'kimia'
        ]);

        //24
        NonConsCategory::create([
            'category_code' => 'G.03',
            'category_name' => 'Biologi',
            'category_slug' => 'biologi'
        ]);

        //25
        NonConsCategory::create([
            'category_code' => 'H.01',
            'category_name' => 'Peraga Biologi',
            'category_slug' => 'peraga-biologi'
        ]);

        //26
        NonConsCategory::create([
            'category_code' => 'H.02',
            'category_name' => 'Peraga Fisika',
            'category_slug' => 'peraga-fisika'
        ]);

        //27
        NonConsCategory::create([
            'category_code' => 'H.03',
            'category_name' => 'Peraga Lainnya',
            'category_slug' => 'peraga-lainnya'
        ]);

        //28
        NonConsCategory::create([
            'category_code' => 'I.01',
            'category_name' => 'Peralatan Dapur',
            'category_slug' => 'peralatan-dapur'
        ]);

        //29
        NonConsCategory::create([
            'category_code' => 'I.02',
            'category_name' => 'Kain',
            'category_slug' => 'kain'
        ]);

        //30
        NonConsCategory::create([
            'category_code' => 'I.03',
            'category_name' => 'Peralatan Kamar Tidur',
            'category_slug' => 'peralatan-kamar-tidur'
        ]);

        //31
        NonConsCategory::create([
            'category_code' => 'J.01',
            'category_name' => 'Hiasan Ruangan Wajib',
            'category_slug' => 'hiasan-ruangan-wajib'
        ]);

        //32
        NonConsCategory::create([
            'category_code' => 'J.02',
            'category_name' => 'Hiasan Ruangan Tambahan',
            'category_slug' => 'hiasan-ruangan-tambahan'
        ]);

        //33
        NonConsCategory::create([
            'category_code' => 'J.03',
            'category_name' => 'Penghargaan',
            'category_slug' => 'penghargaan'
        ]);

        //34
        NonConsCategory::create([
            'category_code' => 'J.04',
            'category_name' => 'Bendera',
            'category_slug' => 'bendera'
        ]);

        //35
        NonConsCategory::create([
            'category_code' => 'J.05',
            'category_name' => 'Kerohanian',
            'category_slug' => 'kerohanian'
        ]);

        //36
        NonConsCategory::create([
            'category_code' => 'K.01',
            'category_name' => 'Papan Tulis',
            'category_slug' => 'papan-tulis'
        ]);

        //37
        NonConsCategory::create([
            'category_code' => 'K.02',
            'category_name' => 'Papan Dinding',
            'category_slug' => 'papan-dinding'
        ]);
    }
}