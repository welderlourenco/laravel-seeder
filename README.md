# Laravel Seeder #

Run one or more seeder files with one and very easy command.

# Introduction #

Have you realized the work it takes for you to comment and uncomment your calls inside the DatabaseSeeder.php file everytime you need to run some Seeder? Well, not anymore, with *Laravel Seeder* you can run your Seeder files without touching in the DatabaseSeeder.php file with a simple command, amazing huh?

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
    // At the end of this array, push Laravel Facebook provider:
    'WelderLourenco\LaravelSeeder\Providers\LaravelSeederServiceProvider'
  ),
)
```

# Usage #

Laravel Seeder adds to your `db` command namespace, two more commands:

```
php artisan db:all
```

This get all your Seeder files existing inside the main `/seeds` folder and run it.

And there's:

```
php artisan db:only --files="UsersTableSeeder,RolesTableSeeder"
```

This recieves the `--files` option, that is the classes you want to run separated by colon.

# Thanks #

Thank God for the knowledge to write all this.