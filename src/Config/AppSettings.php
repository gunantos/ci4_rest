<?php

namespace APPKITA\CI4_REST\Config;

use CodeIgniter\Config\BaseConfig;

class AppSettings extends BaseConfig
{
   public $appName = 'My Application';
   public $author = 'Default Author';
   public $keywords = 'default, keywords';
   public $description = 'This is the default description.';
   public $favicon = 'default-favicon.ico';
   public $is_production = false;
   public $tax = 0;
}
