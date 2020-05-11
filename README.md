# Dainsys Clear Logs Command
Allows to list or delete all laravel...log logs files

## Installation
- Step 1: Add the following entry to your composer.json file:
    ```js
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/Yismen/clear-log-files.git"
        }
    ]
    ```
- Step 2: Run `composer require "dainsys/clear-log-files:>=2.0"` command to install as a dependency
- Step 3: The Package should be auto-discovered by Laravel. However, you could all register it in your config.app file within the providers array:
    ```php
        'providers' => [
            App\Providers\RouteServiceProvider::class,
        ]
    ```

## Ussage
- To just list the log files, run without any argument:
    - `php artisan dainsys:laravel-logs`
- To remove all existing log files, pass the --clear flag 
    - `php artisan dainsys:laravel-logs --clear`
- By default, the last 8 files are keept. To override the number of files to preserve, update the --keep flag
    - `php artisan dainsys:laravel-logs --clear --keep=0`
- By default, the package search for any file starting with 'laravel-'. To override, pass the desired starting file name as an option
    - `php artisan dainsys:laravel-logs new_file_name --clear `
