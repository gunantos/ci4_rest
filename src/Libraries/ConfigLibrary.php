<?php

namespace CI4_REST\Libraries;

use APPKITA\CI4_REST\Models\ConfigModel;
use APPKITA\CI4_REST\Config\AppSettings;

class ConfigLibrary
{
   protected $configModel;
   protected $appSettings;

   public function __construct()
   {
      $this->configModel = new ConfigModel();
      $this->appSettings = new AppSettings();
   }

   public function get($key)
   {
      $config = $this->configModel->getConfigValue($key);

      if ($config && !empty($config['value'])) {
         return $config['value'];
      } else {
         return $this->appSettings->{$key};
      }
   }

   public function set($key, $value)
   {
      // Periksa apakah key sudah ada
      $existingConfig = $this->configModel->getConfigValue($key);

      if ($existingConfig) {
         // Update konfigurasi yang ada
         $this->configModel->update($existingConfig['id'], ['value' => $value]);
      } else {
         // Insert konfigurasi baru
         $this->configModel->insert(['name' => $key, 'value' => $value]);
      }
   }
}
