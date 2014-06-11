# Laravel Seeder #

Run one or more of your seeder files with this very easy command.

# Introduction #

Did you notice the work it takes to perform database seeding in Laravel nowadays? Uncomment, comment and uncomment your calls again. Well, not anymore, with *Laravel Seeder* all the `boring` and `unnecessary` work it's done! Here you can perform a database seeding with only one command without having to modify your DatabaseSeeder.php file. Amazing huh?

# Instalation #

## Required steps ##

In the ***require*** key of master ***composer.json*** file add the following.


```php
"welderlourenco/laravel-seeder" : "dev-master"
```

Run the Composer update comand


```
composer update
```

Once this operation completes, the final step is to add the ***provider*** in the ***app/config/app.php*** config file.


```php
return array(

  // ...
  'providers' => array(
  	// ...
    'WelderLourenco\LaravelSeeder\Providers\LaravelSeederServiceProvider'
  ),

)
```

# Usage #

*Laravel Seeder* adds to your `db` command namespace, two more commands:

```
php artisan db:all
```
It will search for all Seeder files inside your `/seeds` folder and run them.


```
php artisan db:only --files="UserTableSeeder"
```
It will run only the files you specify in the `--files` option, please, notice that you can pass
multiple files separated by colon: `--files="UserTableSeeder, RoleTableSeeder, PermissionTableSeeder"`


# Thanks #

Thank God for the knowledge to write all this.