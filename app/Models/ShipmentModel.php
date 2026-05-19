<?php

namespace App\Models;

use CodeIgniter\Model;

class ShipmentModel extends Model
{
    protected $table            = 'shipments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'awb',
        'created_by_user_id',
        'sender_customer_id',
        'receiver_customer_id',
        'origin_location_id',
        'destination_location_id',
        'service_id',
        'item_name',
        'item_desc',
        'qty',
        'weight_kg',
        'length_cm',
        'width_cm',
        'height_cm',
        'is_fragile',
        'declared_value',
        'shipping_fee',
        'insurance_fee',
        'total_amount',
        'current_status',
        'created_at',
        'updated_at',
        'pickup_outlet_id',         // Tambahkan ini
        'delivery_outlet_id',       // Tambahkan ini
        'current_outlet_id',        // Tambahkan ini
        'manifest_id',              // Tambahkan ini
        'estimated_delivery_date',  // Tambahkan ini
        'payment_status',           // Tambahkan ini
        'cod_amount',               // Tambahkan ini
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
