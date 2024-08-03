<template>
    <base-layout title="Panduan">
        <div class="content content-full">
            <!-- Hero -->
            <div class="block block-rounded block-transparent bg-primary">
                <div class="block-content bg-black-25">
                    <div class="py-3 text-center">
                        <h1 class="h2 fw-bold text-white mb-2">Panduan Pemesanan</h1>
                        <h2 class="h5 fw-medium text-white-75">Toko Kombet</h2>
                    </div>
                </div>
            </div>
            <!-- END Hero -->

            <div class="block block-rounded">
                <div class="block-content">
                    <h3 class="fs-4">1. Pendaftaran / Register</h3>
                    <ul class="fa-ul">
                        <li><i class="fa fa-check fa-li"></i>Silahkan klik tombol "Daftar" terlebih dahulu untuk memulai register</li>
                        <li><i class="fa fa-check fa-li"></i>Jika belum memiliki akun, pelanggan harus membuatnya dengan mengklik opsi "Daftar" atau "Buat Akun."</li>
                        <li><i class="fa fa-check fa-li"></i>Jika sudah memiliki akun, pelanggan tinggal memasukkan username dan password yang sudah didaftarkan sebelumnya</li>
                    </ul>
                    
                    <h3 class="fs-4">2. Jelajahi Produk</h3>
                    <ul class="fa-ul">
                        <li><i class="fa fa-check fa-li"></i>Pelanggan dapat menjelajahi kategori produk atau menggunakan fitur pencarian untuk menemukan barang yang diinginkan</li>
                        <li><i class="fa fa-check fa-li"></i>Klik pada produk untuk melihat detail, deskripsi, dan harga</li>
                    </ul>
                    
                    <h3 class="fs-4">3. Tambahkan ke Keranjang</h3>
                    <ul class="fa-ul">
                        <li><i class="fa fa-check fa-li"></i>Setelah memilih produk, pelanggan dapat menambahkannya ke keranjang belanja dengan mengklik tombol "Tambah ke Keranjang".</li>
                        <li><i class="fa fa-check fa-li"></i>Keranjang belanja akan menampilkan ringkasan pesanan, dan pelanggan dapat memilih untuk melanjutkan berbelanja atau menuju checkout.</li>
                    </ul>
                    
                    <h3 class="fs-4">4. Proses Checkout</h3>
                    <ul class="fa-ul">
                        <li><i class="fa fa-check fa-li"></i>Pada halaman checkout, pelanggan diminta untuk memeriksa dan memastikan pesanan mereka.</li>
                        <li><i class="fa fa-check fa-li"></i>Mereka akan diminta untuk mengisi informasi pengiriman, berupa alamat untuk dilakukan pengiriman barang nanti.</li>
                    </ul>
                    
                    <h3 class="fs-4">5. Pembayaran</h3>
                    <ul class="fa-ul">
                        <li><i class="fa fa-check fa-li"></i>Pelanggan akan membayar secara via transfer untuk menyelesaikan proses pesanan.</li>
                        <li><i class="fa fa-check fa-li"></i>Upload bukti pembayaran pada form yang sudah disediakan dan tunggu untuk proses  verifikasi.</li>
                    </ul>
                    
                    <h3 class="fs-4">6. Pengerjaan Pesanan</h3>
                    <ul class="fa-ul">
                        <li><i class="fa fa-check fa-li"></i>Pesanan akan dikonfirmasi oleh toko setelah pelanggan melunasi pembayaran dan akan langsung diproses.</li>
                        <li><i class="fa fa-check fa-li"></i>Ketika barang sudah selesai, maka siap untuk dikirimkan</li>
                    </ul>
                    
                    <h3 class="fs-4">7. Lacak Status Pemesanan</h3>
                    <ul class="fa-ul">
                        <li><i class="fa fa-check fa-li"></i>Setelah pesanan dikonfirmasi, pelanggan dapat mengklik status pesanan untuk memantau pengiriman.</li>
                        <li><i class="fa fa-check fa-li"></i>Informasi ini dapat diakses melalui akun pelanggan pada halaman pesanan.</li>
                    </ul>

                    <h3 class="fs-4">8. Lacak Status Pemesanan</h3>
                    <ul class="fa-ul">
                        <li><i class="fa fa-check fa-li"></i>Pelanggan harus mengecek pesanan yang telah diantar oleh karyawan toko</li>
                        <li><i class="fa fa-check fa-li"></i>Setelah pesanan diterima, pelanggan akan diminta untuk melakukan konfirmasi pesanan dengan mengklik tombol “ pesanan telah diterima “</li>
                    </ul>

                    <h2 class="fs-3 text-center">HUBUNGI KAMI</h2>
                    <p class="text-center">Jika ada hal yang ingin ditanyakan secara langsung terkait pesanan atau kendala dalam pengiriman  silahkan hubungi : 082268204595</p>
                </div>
            </div>
        </div>
    </base-layout>
</template>

<script>
export default {
    components: {

    },
    props : {
        data : {
            type : Object,
        }
    },
}
</script>