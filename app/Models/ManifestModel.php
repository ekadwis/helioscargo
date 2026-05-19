<?php

namespace App\Models;

use CodeIgniter\Model;

class ManifestModel extends Model
{
    protected $table      = 'manifests';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'manifest_number', 'origin_outlet_id', 'destination_hub_id',
        'vehicle_number', 'driver_name', 'total_shipments',
        'total_weight', 'status', 'departed_at', 'arrived_at',
        'created_by', 'created_at',
    ];

    protected $useTimestamps = false;
}