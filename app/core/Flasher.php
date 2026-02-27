<?php
class Flasher {
    public static function setFlash($pesan, $aksi, $tipe) {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe // success, error, warning, info
        ];
    }

    public static function flash() {
        if (isset($_SESSION['flash'])) {
            $tipe = $_SESSION['flash']['tipe'];
            $title = ucfirst($tipe);
            $pesan = $_SESSION['flash']['pesan'];
            $aksi = $_SESSION['flash']['aksi'];
            
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: '{$title}!',
                            text: 'Data {$pesan} berhasil {$aksi}',
                            icon: '{$tipe}',
                            background: 'rgba(20, 20, 20, 0.85)',
                            color: '#ffffff',
                            confirmButtonColor: '#4abfe8',
                            customClass: {
                                popup: 'glass-popup'
                            },
                            backdrop: `rgba(0,0,0,0.6)`
                        });
                    });
                  </script>";
                  
            unset($_SESSION['flash']);
        }
    }
}
