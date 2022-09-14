<?php

namespace Admn\Admn;

use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;

class AdmnServiceProvider extends PackageServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        AuditLogger::setCredentials(config('admn.token'), config('admn.secret'));
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('admn-laravel')
            ->hasConfigFile(['admn'])
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('admnio/admn-laravel')
                    ->endWith(function(InstallCommand $command) {
                        $command->info('Happy Auditing!');
                    });
            });
    }
}
