<?php

namespace App\Models;

use CodeIgniter\Model;

class ManifestItemModel extends Model
{
    protected $table      = 'manifest_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'manifest_id', 'shipment_id', 'scanned_at', 'scanned_by',
    ];

    protected $useTimestamps = false;
}