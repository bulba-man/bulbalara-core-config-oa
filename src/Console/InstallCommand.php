<?php

namespace Bulbalara\CoreConfigOa\Console; use Illuminate\Console\Command;

;

class InstallCommand extends Command
{
    protected $signature = 'config-oa:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the core config for Opea Admin package';

    public function handle(): void
    {
        $this->installConfigPackage();
        $this->initDatabase();
//        $this->installOpenAdminExt();
    }

    protected function installOpenAdminExt(): void
    {
        $this->call('admin:import', ['extension' => \Bulbalara\CoreConfigOa\CoreConfigExtension::PACKAGE_NAME]);
    }

    protected function installConfigPackage(): void
    {
        $this->call('coreconfig:install');
    }

    protected function initDatabase(): void
    {
        $this->call('migrate', [
            '--path' => __DIR__.'/../../database/migrations/',
            '--realpath' => true
        ]);

        $this->call('db:seed', [
            '--class' => \Bulbalara\CoreConfigOa\Database\ConfigSeeder::class
        ]);
    }
}
