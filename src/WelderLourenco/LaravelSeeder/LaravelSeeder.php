<?php namespace WelderLourenco\LaravelSeeder;

use Illuminate\Filesystem\Filesystem;

class LaravelSeeder 
{

	/**
	 * The default content of the DatabaseSeeder.php file.
	 *
	 * @var  string
	 */	
	private $defaultContent = '<?php class DatabaseSeeder extends Seeder { public function run() { Eloquent::unguard(); {calls} } }';

	/**
	 * The output that will be echo in the console. Stored in a array.
	 * 
	 * @var string
	 */
	private $output = array();

	/**
	 * Holds the Filesystem object.
	 * 
	 * @var Filesystem
	 */
	private $filesystem;

	/**
	 * The stored DatabaseSeeder.php file content goes here.
	 * 
	 * @var string
	 */
	private $databaseSeederContent = '';

	/**
	 * The classes who will be written in the DatabaseSeeder.php file.
	 * 
	 * @var array
	 */
	private $classes = array();

	public function __construct()
	{
		$this->setFilesystem();
	}

	/**
	 * Set the filesystem property to the filesystem object.
	 * 
	 */
	public function setFilesystem()
	{
		$this->filesystem = new Filesystem;	
	}

	/**
	 * Get the filesystem object instance.
	 * 
	 */
	public function getFilesystem()
	{
		return $this->filesystem;
	}

	/**
	 * Set a new message to the output array.
	 *
	 * @param  string $message
	 */
	public function setOutput($message)
	{
		$this->output[] = $message;
	}

	/**
	 * Get the output array.
	 * 
	 * @return array
	 */
	public function getOutput()
	{
		return $this->output;
	}

	/**
	 * Set the classes.
	 *
	 * @param  array $classes
	 */
	public function setClasses($classes)
	{
		$this->classes = $classes;
	}

	/**
	 * Get the classes.
	 *
	 * @return array
	 */
	public function getClasses()
	{
		return $this->classes;
	}

	/**
	 * Set the content of the current DatabaseSeeder.php file.
	 *
	 * @param  array $classes
	 */
	public function setDatabaseSeederContent()
	{
		$this->databaseSeederContent = $this->getFilesystem()->get(app_path() . '/database/seeds/DatabaseSeeder.php');
	}

	/**
	 * Get the content of the current DatabaseSeeder.php file.
	 *
	 * @return array
	 */
	public function getDatabaseSeederContent()
	{
		return $this->databaseSeederContent;
	}

	/**
	 * Read the main seeds folder and filter the extended by Seeder files.
	 *
	 * @param  string $list
	 * @return array
	 */
	public function filteredFiles($list = '')
	{
		$list = array_where(explode(',', $list), function($key, $value)
		{
			return ($value != '') ?: false;
		});

		$files = $this->getFilesystem()->allFiles(app_path() . '/database/seeds');

		$filtered = array();

		if (count($list) > 0)
		{
			foreach ($list as $class)
			{
				foreach ($files as $file)
				{
					if (strpos(file_get_contents($file->getPathName()), 'extends Seeder') != false)
					{
						if ($file->getFileName() != 'DatabaseSeeder.php')
						{
							$className = str_replace('.php', '', $file->getFileName());

							if ($className == $class)
							{
								$filtered[] = $className;
							}
							else
							{
								$this->setOutput('[' . $class . '] doesn\'t exists or isn\'t valid.');
							}
						}
					}
				}	
			}
		}
		else
		{
			foreach ($files as $file)
			{
				if (strpos(file_get_contents($file->getPathName()), 'extends Seeder') != false)
				{
					if ($file->getFileName() != 'DatabaseSeeder.php')
					{
						$className = str_replace('.php', '', $file->getFileName());
						$filtered[] = $className;
					}
				}
			}		
		}

		return $filtered;
	}

	/**
	 * Write the new database seeder inside the DatabaseSeeder.php file.
	 * 
	 */
	public function writeDatabaseSeeder()
	{
		$string = '';

		foreach ($this->getClasses() as $class)
		{
			$string .= '$this->call(\'' . $class . '\');';
		}
		
		$this->getFilesystem()->put(app_path() . '/database/seeds/DatabaseSeeder.php', str_replace('{calls}', $string, $this->defaultContent));
	}

	/**
	 * Restore the DatabaseSeeder.php old file.
	 * 	 
	 */
	public function restoreDatabaseSeederContent()
	{
		$this->getFilesystem()->put(app_path() . '/database/seeds/DatabaseSeeder.php',$this->getDatabaseSeederContent());
	}
}
