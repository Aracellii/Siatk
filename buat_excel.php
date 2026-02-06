<?php
$nama_file = 'data_barang_10k_unik_1k.csv';
$header = ['kode_barang', 'nama_barang', 'stok', 'nama_bagian'];
$bagian = [
    'Tata Usaha',
    'Survei dan Pemetaan',
    'Penetapan Hak dan Pendaftaran',
    'Penataan dan Pemberdayaan',
    'Pengadaan Tanah dan Pengembangan',
    'Pengendalian dan Penanganan Sengketa'
];

$file = fopen($nama_file, 'w');
fputcsv($file, $header);

// 1. Tentukan jumlah barang unik yang diinginkan
$jumlahBarangUnik = 1000;
$totalBaris = 10000;

for ($i = 1; $i <= $totalBaris; $i++) {
    // 2. Gunakan operator Modulo (%) agar angka kembali ke 1 setelah mencapai 1000
    // (i-1) % 1000 akan menghasilkan 0 sampai 999. Ditambah 1 jadi 1 sampai 1000.
    $idBarang = (($i - 1) % $jumlahBarangUnik) + 1;
    
    $kodeBarang = "BRG-" . str_pad($idBarang, 4, '0', STR_PAD_LEFT);
    $namaBarang = "Barang Contoh Ke-" . $idBarang;
    
    fputcsv($file, [
        $kodeBarang,
        $namaBarang,
        rand(10, 500),
        $bagian[array_rand($bagian)] // Bagian tetap acak
    ]);
}

fclose($file);
echo "File $nama_file berhasil dibuat! Total 10.000 baris dengan 1.000 barang unik.";