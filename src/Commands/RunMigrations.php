<?php

namespace APPKITA\CI4_REST\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RunMigrations extends BaseCommand
{
   protected $group = 'CI4_REST';
   protected $name = 'ci4rest:migrate';
   protected $description = 'Run migrations and seed for CI4_REST library';

   public function run(array $params)
   {
      CLI::write('Running CI4_REST migrations...', 'yellow');
      service('migrations')->setNamespace('APPKITA\CI4_REST')->latest();
      CLI::write('Migrations completed!', 'green');

      CLI::write('Running CI4_REST seeders...', 'yellow');
      $seeder = \Config\Database::seeder();
      $seeder->call('APPKITA\CI4_REST\Database\Seeds\SampleSeeder');
      CLI::write('Seeding completed!', 'green');
   }
}
