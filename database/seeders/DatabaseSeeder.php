<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\KategoriBahan;
use App\Models\Satuan;
use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\Menu;
use App\Models\Resep;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name'=>'Administrator','username'=>'admin','email'=>'admin@mbg.local','password'=>Hash::make('admin123'),'role'=>'admin','is_active'=>true]);
        User::create(['name'=>'Petugas Gudang','username'=>'gudang','email'=>'gudang@mbg.local','password'=>Hash::make('gudang123'),'role'=>'petugas_gudang','is_active'=>true]);
        User::create(['name'=>'Petugas Dapur','username'=>'dapur','email'=>'dapur@mbg.local','password'=>Hash::make('dapur123'),'role'=>'petugas_dapur','is_active'=>true]);

        $kategoris = [
            ['nama_kategori'=>'Bahan Pokok','keterangan'=>'Beras, mie, dan sejenisnya'],
            ['nama_kategori'=>'Protein Hewani','keterangan'=>'Daging, ikan, telur, dan sejenisnya'],
            ['nama_kategori'=>'Protein Nabati','keterangan'=>'Tahu, tempe, kacang-kacangan'],
            ['nama_kategori'=>'Sayuran','keterangan'=>'Segala jenis sayuran'],
            ['nama_kategori'=>'Bumbu','keterangan'=>'Bawang, cabai, rempah-rempah'],
            ['nama_kategori'=>'Minyak & Lemak','keterangan'=>'Minyak goreng, mentega, dan sejenisnya'],
            ['nama_kategori'=>'Kecap & Saus','keterangan'=>'Kecap, saus, dan bumbu cair'],
            ['nama_kategori'=>'Buah','keterangan'=>'Segala jenis buah segar'],
            ['nama_kategori'=>'Minuman','keterangan'=>'Susu, jus kemasan, dan sejenisnya'],
        ];
        foreach ($kategoris as $k) KategoriBahan::create($k);

        $satuans = [
            ['nama_satuan'=>'Gram','singkatan'=>'gr'], ['nama_satuan'=>'Kilogram','singkatan'=>'kg'],
            ['nama_satuan'=>'Mililiter','singkatan'=>'ml'], ['nama_satuan'=>'Liter','singkatan'=>'L'],
            ['nama_satuan'=>'Butir','singkatan'=>'btr'], ['nama_satuan'=>'Buah','singkatan'=>'bh'],
            ['nama_satuan'=>'Bungkus','singkatan'=>'bgks'], ['nama_satuan'=>'Karton','singkatan'=>'krt'],
            ['nama_satuan'=>'Sak','singkatan'=>'sak'], ['nama_satuan'=>'Porsi','singkatan'=>'prs'],
        ];
        foreach ($satuans as $s) Satuan::create($s);

        Supplier::create(['nama_supplier'=>'Supplier Demo Sumber Jaya','kontak'=>'08123456789','alamat'=>'Tambun Selatan','is_active'=>true]);

        $gram = Satuan::where('singkatan','gr')->first();
        $ml = Satuan::where('singkatan','ml')->first();
        $butir = Satuan::where('singkatan','btr')->first();
        $buah = Satuan::where('singkatan','bh')->first();
        $bahanPokok = KategoriBahan::where('nama_kategori','Bahan Pokok')->first();
        $protein = KategoriBahan::where('nama_kategori','Protein Hewani')->first();
        $bumbu = KategoriBahan::where('nama_kategori','Bumbu')->first();
        $minyak = KategoriBahan::where('nama_kategori','Minyak & Lemak')->first();

        $beras = BahanBaku::create(['kode_bahan'=>'BHN0001','nama_bahan'=>'Beras','kategori_id'=>$bahanPokok->id,'satuan_id'=>$gram->id,'stok_saat_ini'=>50000,'stok_minimum'=>5000,'harga_beli'=>15000,'is_active'=>true]);
        $telur = BahanBaku::create(['kode_bahan'=>'BHN0002','nama_bahan'=>'Telur','kategori_id'=>$protein->id,'satuan_id'=>$butir->id,'stok_saat_ini'=>500,'stok_minimum'=>50,'harga_beli'=>2500,'is_active'=>true]);
        $minyakGoreng = BahanBaku::create(['kode_bahan'=>'BHN0003','nama_bahan'=>'Minyak Goreng','kategori_id'=>$minyak->id,'satuan_id'=>$ml->id,'stok_saat_ini'=>10000,'stok_minimum'=>1000,'harga_beli'=>18000,'is_active'=>true]);
        $bawangPutih = BahanBaku::create(['kode_bahan'=>'BHN0004','nama_bahan'=>'Bawang Putih','kategori_id'=>$bumbu->id,'satuan_id'=>$gram->id,'stok_saat_ini'=>5000,'stok_minimum'=>500,'harga_beli'=>35000,'is_active'=>true]);

        $nasiGoreng = Menu::create(['kode_menu'=>'MKN001','nama_menu'=>'Nasi Goreng','jenis_menu'=>'makanan','is_active'=>true]);
        Menu::create(['kode_menu'=>'MIN001','nama_menu'=>'Susu Kotak','jenis_menu'=>'minuman','is_active'=>true]);
        Menu::create(['kode_menu'=>'BUH001','nama_menu'=>'Pisang','jenis_menu'=>'buah','is_active'=>true]);

        Resep::create(['menu_id'=>$nasiGoreng->id,'bahan_baku_id'=>$beras->id,'jumlah_per_porsi'=>150,'satuan_id'=>$gram->id]);
        Resep::create(['menu_id'=>$nasiGoreng->id,'bahan_baku_id'=>$telur->id,'jumlah_per_porsi'=>1,'satuan_id'=>$butir->id]);
        Resep::create(['menu_id'=>$nasiGoreng->id,'bahan_baku_id'=>$minyakGoreng->id,'jumlah_per_porsi'=>10,'satuan_id'=>$ml->id]);
        Resep::create(['menu_id'=>$nasiGoreng->id,'bahan_baku_id'=>$bawangPutih->id,'jumlah_per_porsi'=>5,'satuan_id'=>$gram->id]);
    }
}