<?php namespace WelderLourenco\LaravelSeeder\Commands;

use \Illuminate\Console\Command;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Input\InputArgument;

class LaravelSeederOnlyCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:only';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run a list of given seeder files.';

	/**
	 * Hold the LaravelSeeder object.
	 * 
	 * @var LaravelSeeder
	 */
	protected $laravelSeeder;

	/**
	 * Set the laravelSeeder object.
	 * 
	 */
	public function setLaravelSeeder()
	{
		$this->laravelSeeder = new \WelderLourenco\LaravelSeeder\LaravelSeeder;
	}

	/**
	 * Get the LaravelSeeder object.
	 * 
	 * @return LaravelSeeder
	 */
	public function getLaravelSeeder()
	{
		return $this->laravelSeeder;
	}

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->setLaravelSeeder();
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		if ($this->option('files') != '')
		{
			$seeder = $this->getLaravelSeeder();

			$seeder->only($this->option('files'));

			$this->call('db:seed');

			$seeder->restore();

			if ($seeder->getSeeded() == 0)
			{
				$this->info('No seeders were ran!');
			}
		}
		else
		{
			throw new \InvalidArgumentException('The "--files" option is required.');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('files', null, InputOption::VALUE_REQUIRED, 'You can separate the files by colon.', null)
		);
	}

}
