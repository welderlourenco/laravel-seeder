<?php namespace WelderLourenco\LaravelSeeder;

use Illuminate\Filesystem\Filesystem;

class LaravelSeeder
{
	/**
	 * Holds the Filesystem object.
	 * 
	 * @var Filesystem
	 */
	private $filesystem;

	/**
	 * Holds the copied content from DatabaseSeeder.php.
	 * 
	 * @var string
	 */
	private $copied;

	/**
	 * Holds an array of compatible seeder files.
	 * 
	 * @var array
	 */
	private $files = [];	

	/**
	 * The default content of the DatabaseSeeder.php file.
	 *
	 * @var  string
	 */	
	private $pattern = '<?php /** Script generated automatically by Laravel-Seeder. Please, if you are seeing this script you can recover your old DatabaseSeeder.php in DatabaseSeeder.old file. */ class DatabaseSeeder extends Seeder { public function run() { Eloquent::unguard(); {calls} } }';

	/**
	 * The number of ran seeders.
	 * 
	 * @var integer
	 */
	private $seeded = 0;

	/**
	 * Set the filesystem property to the filesystem object.
	 * 
	 */
	private function setFilesystem()
	{
		$this->filesystem = new Filesystem;	
	}

	/**
	 * Get the filesystem object instance.
	 * 
	 */
	private function getFilesystem()
	{
		return $this->filesystem;
	}

	/**
	 * Set the copied content.
	 * 
	 */
	private function setCopied($copied)
	{
		$this->copied = $copied;	
	}

	/**
	 * Get the copied content.
	 * 
	 */
	private function getCopied()
	{
		return $this->copied;
	}

	/**
	 * Set the compatible files.
	 * 
	 */
	private function setFiles($files)
	{
		$this->files = $files;	
	}

	/**
	 * Get the compatible files.
	 * 
	 */
	private function getFiles()
	{
		return $this->files;
	}

	/**
	 * Get the compatible files.
	 * 
	 */
	private function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * Set the seeded count.
	 * 
	 */
	private function setSeeded($seeded)
	{
		$this->seeded = $seeded;	
	}

	/**
	 * Get the seeded count.
	 * 
	 */
	public function getSeeded()
	{
		return $this->seeded;
	}

	public function __construct()
	{
		$this->setFilesystem();
	}

	/**
	 * @version  1.0.0
	 * Copy the current DatabaseSeeder.php file and save it to the proper attribute.
	 * 
	 * @version  1.0.1
	 * Copy the current DatabaseSeeder.php file and save it to a new file called DatabaseSeeder.old
	 * 
	 */
	private function copy()
	{
		$this->setCopied($this->getFilesystem()->get(app_path() . '/database/seeds/DatabaseSeeder.php'));

		$this->getFilesystem()->put(app_path() . '/database/seeds/DatabaseSeeder.old', $this->getCopied());
	}

	/**
	 * Get an array of all the files that is actually compatible with the files we're looking for.
	 * 
	 */
	private function read()
	{
		$files = $this->getFilesystem()->allFiles(app_path() . '/database/seeds');

		$filtered = [];

		foreach ($files as $file)
		{
			if (strpos(file_get_contents($file->getPathName()), 'extends Seeder') != false)
			{
				if ($file->getFileName() != 'DatabaseSeeder.php')
				{
					$filtered[] = $file->getFileName();
				}
			}
		}

		$this->setFiles($filtered);
	}

	/**
	 * Write the new DatabaseSeeder.php
	 * 
	 */
	private function write()
	{
		$content = '';

		foreach ($this->getFiles() as $file)
		{
			$content .= '$this->call(\'' . str_replace('.php', '', $file) . '\');';	
		}
		
		$databaseSeeder = str_replace('{calls}', $content, $this->getPattern());

		$this->getFilesystem()->put(app_path() . '/database/seeds/DatabaseSeeder.php', $databaseSeeder);
	}

	/**
	 * Restore the DatabaseSeeder.php file to it's previous version.
	 * 
	 */
	public function restore()
	{
		$this->getFilesystem()->put(app_path() . '/database/seeds/DatabaseSeeder.php', $this->getCopied());

		$this->getFilesystem()->delete(app_path() . '/database/seeds/DatabaseSeeder.old');
	}

	/**
	 * Copy the current DatabaseSeeder.php file, read the /seeds directory in search for compatible files,
	 * write the new DatabaseSeeder.php file and return true if it's alright.
	 * 
	 * @return boolean
	 */
	public function all()
	{
		$this->copy();

		$this->read();

		$this->write();

		$this->setSeeded(count($this->getFiles()));

		return true;
	}

	/**
	 * Recieves the string list entered by the developer, explode it and only return the ones filtered.
	 * 
	 * @param  string $list
	 * @return array
	 */
	public function getList($list)
	{
		$filteredList = [];

		foreach (explode(',', $list) as $item)
		{
			if ($item != '')
			{
				$filteredList[] = $item;
			}
		}

		return $filteredList;
	}

	/**
	 * Prepare the stage for the db:only command to work properly.
	 * 
	 */
	public function readForOnly($list)
	{
		$this->read();

		$files = $this->getFiles();

		$filtered = [];

		foreach ($this->getList($list) as $list)
		{
			$list = trim($list);
			
			if (in_array($list, $files) || in_array($list . '.php', $files))
			{
				$filtered[] = $list;
			}
		}

		$this->setFiles($filtered);
	}

	/**
	 * Copy the current DatabaseSeeder.php file, loop through each file and verify it's idententy with ther
	 * files array, write the new DatabaseSeeder.php file and return true if it's alright.
	 * 
	 * @param  string $list
	 * @return boolean
	 */
	public function only($list)
	{
		$this->copy();

		$this->readForOnly($list);

		$this->write();

		$this->setSeeded(count($this->getFiles()));

		return true;
	}
}
