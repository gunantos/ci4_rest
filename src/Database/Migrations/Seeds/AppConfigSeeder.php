<?php

namespace APPKITA\CI4_REST\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AppConfigSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'  => 'appName',
                'value' => 'My Custom Application',
            ],
            [
                'name'  => 'author',
                'value' => 'Custom Author',
            ],
            [
                'name'  => 'tax',
                'value' => 0,
            ],
            [
                'name'  => 'keywords',
                'value' => 'Custom Author',
            ],
            [
                'name'  => 'description',
                'value' => 'Custom Author',
            ],
            [
                'name'  => 'merchant_id',
                'value' => 'Custom Author',
            ],
            [
                'name'  => 'client_key',
                'value' => 'Custom Author',
            ],
            [
                'name'  => 'server_key',
                'value' => 'Custom Author',
            ],
            [
                'name'  => 'is_production',
                'value' => '0',
            ],
            [
                'name'  => 'waktu',
                'value' => '08 - 10 November 2024',
            ],
            [
                'name' => 'penjualan_tiket',
                'value' => 1
            ]
            // Tambahkan entri lainnya di sini
        ];

        // Menggunakan query builder untuk memasukkan data
        $this->db->table('app_config')->insertBatch($data);
    }
}
