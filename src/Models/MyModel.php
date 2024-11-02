<?php

namespace APPKITA\CI4_REST\Models;

use CodeIgniter\Model;

class MyModel extends Model
{
    protected $table            = '';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
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


    public function getAttributePrimary()
    {
        return $this->primaryKey;
    }

    public function get_datatables($searchValue, $start, $length, $orderColumn = null, $orderDirection = 'ASC')
    {
        $columns = $this->allowedFields;
        $builder = $this->db->table($this->table);

        if ($searchValue) {
            $builder->groupStart(); // Start grouping conditions
            foreach ($columns as $column) {
                $builder->orLike($column, $searchValue);
            }
            $builder->groupEnd(); // End grouping conditions
        }

        if ($orderColumn !== null) {
            if (in_array($orderColumn, $columns)) {
                $builder->orderBy($this->table . '.' . $orderColumn, $orderDirection);
            } else {
                $builder->orderBy($orderColumn, $orderDirection);
            }
        }
        return $builder->limit($length, $start)->get()->getResult();
    }

    public function get_filtered_count($searchValue)
    {
        $builder = $this->db->table($this->table);

        $columns = $this->allowedFields;
        if ($searchValue) {
            $builder->groupStart(); // Start grouping conditions
            foreach ($columns as $column) {
                $builder->orLike($column, $searchValue);
            }
            $builder->groupEnd(); // End grouping conditions
        }

        return $builder->countAllResults();
    }

    public function get_all_count()
    {
        return $this->countAll();
    }
}
