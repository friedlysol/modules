<?php

namespace Caffeinated\Modules\Console;

use Caffeinated\Modules\Handlers\ModuleMakeManualMigrationHandler;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMakeManualMigrationCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:make-manual-migration';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new module migration file';

	/**
	 * @var ModuleMakeManualMigrationHandler
	 */
	protected $handler;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(ModuleMakeManualMigrationHandler $handler)
	{
		parent::__construct();

		$this->handler = $handler;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		return $this->handler->fire($this, $this->argument('module'), $this->argument('table'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['module', InputArgument::REQUIRED, 'Module slug.'],
			['table', InputArgument::REQUIRED, 'Table name.']
		];
	}
}
