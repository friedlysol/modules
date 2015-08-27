<?php
namespace Caffeinated\Modules\Providers;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
	/**
	* @var bool $defer Indicates if loading of the provider is deferred.
	*/
	protected $defer = false;

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerMakeCommand();
		$this->registerEnableCommand();
		$this->registerDisableCommand();
		$this->registerMakeMigrationCommand();
		$this->registerMakeRequestCommand();
		$this->registerMigrateCommand();
		$this->registerMigrateRefreshCommand();
		$this->registerMigrateResetCommand();
		$this->registerMigrateRollbackCommand();
		$this->registerSeedCommand();
		$this->registerListCommand();
        $this->registerMakeManualMigrationCommand();

		$this->commands([
			'modules.make',
			'modules.enable',
			'modules.disable',
			'modules.makeMigration',
			'modules.makeRequest',
			'modules.migrate',
			'modules.migrateRefresh',
			'modules.migrateReset',
			'modules.migrateRollback',
			'modules.seed',
			'modules.list',
            'modules.makeManualMigration',
		]);
	}

	/**
	 * Register the "module:enable" console command.
	 *
	 * @return Console\ModuleEnableCommand
	 */
	protected function registerEnableCommand()
	{
		$this->app->bindShared('modules.enable', function() {
			return new \Caffeinated\Modules\Console\Commands\ModuleEnableCommand;
		});
	}

	/**
	 * Register the "module:disable" console command.
	 *
	 * @return Console\ModuleDisableCommand
	 */
	protected function registerDisableCommand()
	{
		$this->app->bindShared('modules.disable', function() {
			return new \Caffeinated\Modules\Console\Commands\ModuleDisableCommand;
		});
	}

	/**
	 * Register the "module:make" console command.
	 *
	 * @return Console\ModuleMakeCommand
	 */
	protected function registerMakeCommand()
	{
		$this->app->bindShared('modules.make', function($app) {
			$handler = new \Caffeinated\Modules\Handlers\Console\Commands\ModuleMakeHandler($app['modules'], $app['files']);

			return new \Caffeinated\Modules\Console\Commands\ModuleMakeCommand($handler);
		});
	}

	/**
	 * Register the "module:make:migration" console command.
	 *
	 * @return Console\ModuleMakeMigrationCommand
	 */
	protected function registerMakeMigrationCommand()
	{
		$this->app->bindShared('modules.makeMigration', function($app) {
			$handler = new \Caffeinated\Modules\Handlers\Console\Commands\ModuleMakeMigrationHandler($app['modules'], $app['files']);

			return new \Caffeinated\Modules\Console\Commands\ModuleMakeMigrationCommand($handler);
		});
	}

    /**
     * Register the "module:make-manual-migration" console command.
     *
     * @return Console\ModuleMakeMigrationCommand
     */
    protected function registerMakeManualMigrationCommand()
    {
        $this->app->bindShared('modules.makeManualMigration', function($app) {
            $handler = new \Caffeinated\Modules\Handlers\ModuleMakeManualMigrationHandler($app['modules'], $app['files']);

            return new \Caffeinated\Modules\Console\ModuleMakeManualMigrationCommand($handler);
        });
    }

	/**
	 * Register the "module:make:request" console command.
	 *
	 * @return Console\ModuleMakeRequestCommand
	 */
	protected function registerMakeRequestCommand()
	{
		$this->app->bindShared('modules.makeRequest', function($app) {
			$handler = new \Caffeinated\Modules\Handlers\Console\Commands\ModuleMakeRequestHandler($app['modules'], $app['files']);

			return new \Caffeinated\Modules\Console\Commands\ModuleMakeRequestCommand($handler);
		});
	}

	/**
	 * Register the "module:migrate" console command.
	 *
	 * @return Console\ModuleMigrateCommand
	 */
	protected function registerMigrateCommand()
	{
		$this->app->bindShared('modules.migrate', function($app) {
			return new \Caffeinated\Modules\Console\Commands\ModuleMigrateCommand($app['migrator'], $app['modules']);
		});
	}

	/**
	 * Register the "module:migrate:refresh" console command.
	 *
	 * @return Console\ModuleMigrateRefreshCommand
	 */
	protected function registerMigrateRefreshCommand()
	{
		$this->app->bindShared('modules.migrateRefresh', function() {
			return new \Caffeinated\Modules\Console\Commands\ModuleMigrateRefreshCommand;
		});
	}

	/**
	 * Register the "module:migrate:reset" console command.
	 *
	 * @return Console\ModuleMigrateResetCommand
	 */
	protected function registerMigrateResetCommand()
	{
		$this->app->bindShared('modules.migrateReset', function($app) {
			return new \Caffeinated\Modules\Console\Commands\ModuleMigrateResetCommand($app['modules'], $app['files'], $app['migrator']);
		});
	}

	/**
	 * Register the "module:migrate:rollback" console command.
	 *
	 * @return Console\ModuleMigrateRollbackCommand
	 */
	protected function registerMigrateRollbackCommand()
	{
		$this->app->bindShared('modules.migrateRollback', function($app) {
			return new \Caffeinated\Modules\Console\Commands\ModuleMigrateRollbackCommand($app['modules']);
		});
	}

	/**
	 * Register the "module:seed" console command.
	 *
	 * @return Console\ModuleSeedCommand
	 */
	protected function registerSeedCommand()
	{
		$this->app->bindShared('modules.seed', function($app) {
			return new \Caffeinated\Modules\Console\Commands\ModuleSeedCommand($app['modules']);
		});
	}

	/**
	 * Register the "module:list" console command.
	 *
	 * @return Console\ModuleListCommand
	 */
	protected function registerListCommand()
	{
		$this->app->bindShared('modules.list', function($app) {
			return new \Caffeinated\Modules\Console\Commands\ModuleListCommand($app['modules']);
		});
	}
}
