<?php

namespace APPKITA\CI4_REST\Models;

class ConfigModel extends MyModel
{
   protected $table = 'app_config';
   protected $primaryKey = 'id';
   protected $allowedFields = ['name', 'value'];

   public function getConfigValue($name)
   {
      return $this->where('name', $name)->first();
   }
}
