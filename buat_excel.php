<?php
$nama_file = 'data_barang_100.csv';
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

for ($i = 1; $i <= 100; $i++) {
    fputcsv($file, [
        "BRG-" . str_pad($i, 4, '0', STR_PAD_LEFT), // BRG-0001, dst
        "Barang Contoh Ke-" . $i,
        rand(10, 500), // Stok acak 10-500
        $bagian[array_rand($bagian)] // Pilih bagian acak dari list kamu
    ]);
}

fclose($file);
echo "File $nama_file berhasil dibuat dengan 1000 baris!";