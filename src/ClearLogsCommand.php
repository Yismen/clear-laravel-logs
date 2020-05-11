<?php

namespace Dainsys\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:laravel-logs
        {filename? : The pattern with which filename starts.} 
        {--keep=4 : The number of files to keep.}
        {--clear : Remove all files except last (n) files, otherwise just print a list of filenames!}
   ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the storage/logs folder while keeping the last (n) amount of files';

    /**
     * Files placeholder 
     * 
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Filesystem manager
     * 
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->files = $this->startUp();


        if ($this->wantsListOnly()) {
            return $this->info($this->getFileNamesList());
        }

        $this->deleteFiles($this->files);
    }

    /**
     * Return a list of all log files!
     *
     * @param Array $this->files
     * @return Array
     */
    protected function getFileNamesList()
    {
        return $this->files == null
            ? "Nothing to list!"
            : $this->files->map(function ($file) {
                return $file->getFileName();
            });
    }

    /**
     * Delete all the log files, keeping the latest desired to keep.
     *
     * @param Array $this->files
     * @return void
     */
    protected function deleteFiles()
    {
        $this->dropKeepingFiles();

        $count = count($this->files);

        if ($count > 0) {
            $this->files->each(function ($file) {
                $this->filesystem->delete($file);
            });
        }

        $this->warn("{$count} Files Deleted!");
    }

    /**
     * Initialize the command,
     *
     * @return void
     */
    protected function startUp()
    {
        $this->file_name = $this->argument('filename') ?: config('dainsys_clearlogs.prefix');

        return collect(
            $this->filesystem->allFiles(storage_path('logs'))
        )->sortByDesc('mtime')->filter(function ($file) {
            return Str::startsWith($file->getFilename(), $this->file_name);
        });
    }

    /**
     * If the clear option was not passed
     *
     * @return boolean
     */
    protected function wantsListOnly(): bool
    {
        return $this->option('clear') == false;
    }

    /**
     * Remove from the array the (n) number of files that shouldnt be deleted!
     * Returns the files to be deleted
     *
     * @return void
     */
    protected function dropKeepingFiles()
    {
        $keep = (int) $this->option('keep');
        $count = $this->files->count();

        if ($count > $keep) {
            return $this->files->splice($count - $keep);
        }

        return $this->files = [];
    }
}
