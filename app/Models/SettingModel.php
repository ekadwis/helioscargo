<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table      = 'settings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['key', 'value'];

    protected $useTimestamps = false;

    // Ubah format data settings dari database menjadi array berpasangan key dan value agar lebih gampang dipakai
    public function getAllSettings(): array
    {
        $rows = $this->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['key']] = $row['value'];
        }
        return $result;
    }

    // Simpan pengaturan baru, kalau pengaturan (key) sudah ada maka akan di-update valuenya
    public function setSetting(string $key, string $value): void
    {
        $existing = $this->where('key', $key)->first();
        if ($existing) {
            $this->update($existing['id'], ['value' => $value]);
        } else {
            $this->insert(['key' => $key, 'value' => $value]);
        }
    }
}