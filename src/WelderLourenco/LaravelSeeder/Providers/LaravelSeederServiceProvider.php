<?php namespace WelderLourenco\LaravelSeeder\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelSeederServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('welderlourenco/laravel-seeder');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerDbAll();
		$this->registerDbOnly();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	/**
	 * Register the db:all command.
	 * 
	 */
	public function registerDbAll()
	{
		$this->app->bind('welderlourenco::command.db.all', function($app) {
    	return new \WelderLourenco\LaravelSeeder\Commands\LaravelSeederAllCommand();
		});
		$this->commands(array(
		    'welderlourenco::command.db.all'
		));
	}

	/**
	 * Register the db:only command.
	 * 
	 */
	public function registerDbOnly()
	{
		$this->app->bind('welderlourenco::command.db.only', function($app) {
    	return new \WelderLourenco\LaravelSeeder\Commands\LaravelSeederOnlyCommand();
		});
		$this->commands(array(
		    'welderlourenco::command.db.only'
		));
	}

}
