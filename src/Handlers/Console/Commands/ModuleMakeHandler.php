<?php
namespace Caffeinated\Modules\Handlers\Console\Commands;

use Caffeinated\Modules\Modules;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ModuleMakeHandler
{
	/**
	 * @var Console
	 */
	protected $console;

	/**
	 * @var array $folders Module folders to be created.
	 */
	protected $folders = [
		'Console/',
		'Database/',
		'Database/Migrations/',
		'Database/Seeds/',
		'Http/',
		'Http/Controllers/',
		'Http/Requests/',
		'Providers/',
		'Models/',
		'Repositories/',
	];

	/**
	 * Module files to be created.
	 *
	 * @var array
	 */
	protected $files = [
		'Database/Seeds/{{name}}DatabaseSeeder.php',
		'Http/routes.php',
		'Providers/{{name}}ServiceProvider.php',
		'Providers/RouteServiceProvider.php',
		'module.json',
		'Models/{{name}}.php',
		'Repositories/{{name}}Repository.php',
		'Http/Controllers/{{name}}Controller.php',
        'Http//Requests/{{name}}Request.php'
	];

	/**
	 * @var array $stubs Module stubs used to populate defined files.
	 */
	protected $stubs = [
		'seeder.stub',
		'routes.stub',
		'moduleserviceprovider.stub',
		'routeserviceprovider.stub',
		'module.stub',
		'model.stub',
		'repository.stub',
		'controller.stub',
        'request.stub',
	];

	/**
	 * @var \Caffeinated\Modules\Modules
	 */
	protected $module;

	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $finder;

	/**
	 * @var string
	 */
	protected $slug;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * Constructor method.
	 *
	 * @param Modules $module
	 * @param Filesystem $finder
	 */
	public function __construct(Modules $module, Filesystem $finder)
	{
		$this->module = $module;
		$this->finder = $finder;
	}

	/**
	 * Fire off the handler.
	 *
	 * @param Command $console
	 * @param string $slug
	 * @return bool
	 */
	public function fire(Command $console, $slug)
	{
		$this->console = $console;
		$this->slug    = strtolower($slug);
		$this->name    = Str::studly($slug);

		if ($this->module->exists($this->slug)) {
			$console->comment('Module [{$this->name}] already exists.');

			return false;
		}

		$this->generate($console);
	}

	/**
	 * Generate module folders and files.
	 *
	 * @param Command $console
	 * @return bool
	 */
	public function generate(Command $console)
	{
		$this->generateFolders();

        $this->generateGitkeep();

		$this->generateFiles();

		$console->info("Module [{$this->name}] has been created successfully.");

		$this->optimize($console);

		return true;
	}

	/**
	 * Generate defined module folders.
	 *
	 * @return void
	 */
	protected function generateFolders()
	{
		if (! $this->finder->isDirectory($this->module->getPath())) {
			$this->finder->makeDirectory($this->module->getPath());
		}

		$this->finder->makeDirectory($this->getModulePath($this->slug, true));

		foreach ($this->folders as $folder) {
			$this->finder->makeDirectory($this->getModulePath($this->slug).$folder);
		}
	}

	/**
	 * Generate defined module files.
	 *
	 * @return void
	 */
	protected function generateFiles()
	{
		foreach ($this->files as $key => $file) {
			$file = $this->formatContent($file);

			$this->makeFile($key, $file);
		}
	}

	/**
	 * Create module file.
	 *
	 * @param int $key
	 * @param string $file
	 * @return string
	 */
	protected function makeFile($key, $file)
	{
		return $this->finder->put($this->getDestinationFile($file), $this->getStubContent($key));
	}

	/**
	 * Get the path to the module.
	 *
	 * @param  string $slug
	 * @return string
	 */
	protected function getModulePath($slug = null, $allowNotExists = false)
	{
		if ($slug)
			return $this->module->getModulePath($slug, $allowNotExists);

		return $this->module->getPath();
	}

	/**
	 * Get destination file.
	 *
	 * @param  string $file
	 * @return string
	 */
	protected function getDestinationFile($file)
	{
		return $this->getModulePath($this->slug).$this->formatContent($file);
	}

	/**
	 * Get stub content by key.
	 *
	 * @param  int $key
	 * @return string
	 */
	protected function getStubContent($key)
	{
		return $this->formatContent($this->finder->get(__DIR__.'/../../../Console/Stubs/'.$this->stubs[$key]));
	}

	/**
	 * Replace placeholder text with correct values.
	 *
	 * @return string
	 */
	protected function formatContent($content)
	{
		return str_replace(
			['{{slug}}', '{{name}}', '{{namespace}}'],
			[$this->slug, $this->name, $this->module->getNamespace()],
			$content
		);
	}
}
