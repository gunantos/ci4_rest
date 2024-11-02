<?php

use APPKITA\CI4_REST\Models\ConfigModel;
use APPKITA\CI4_REST\Config\AppSettings;


function get_config($key)
{
   $configModel = new ConfigModel();
   $appConfig = $configModel->getConfigValue($key);

   if ($appConfig && !empty($appConfig['value'])) {
      return $appConfig['value'];
   } else {
      // Jika tidak ada di database, ambil dari file config default
      $config = new AppSettings();
      return isset($config->{$key}) ? $config->{$key} : null;
   }
}

function set_config($key, $value)
{
   $configModel = new ConfigModel();

   // Periksa apakah key sudah ada
   $existingConfig = $configModel->getConfigValue($key);

   if ($existingConfig) {
      // Update konfigurasi yang ada
      $configModel->update($existingConfig['id'], ['value' => $value]);
   } else {
      // Insert konfigurasi baru
      $configModel->insert(['name' => $key, 'value' => $value]);
   }
}
