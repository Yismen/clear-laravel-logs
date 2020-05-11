<?php

namespace Dainsys\Commands\Tests;

class ClearLogFilesTest extends TestCase
{
    /** @test */
    public function it_should_delete_all_files_in_log_directory(): void
    {
        $this->createLogFile(['laravel-1.log', 'laravel-2.log']);


        $this->assertFileExists($this->logDirectory . '/laravel-1.log');
        $this->assertFileExists($this->logDirectory . '/laravel-2.log');

        $this->artisan('dainsys:laravel-logs --clear --keep=0');

        $this->assertFileNotExists($this->logDirectory . '/laravel-1.log');
        $this->assertFileNotExists($this->logDirectory . '/laravel-2.log');
    }

    /** @test */
    public function it_should_not_delete_dot_files_in_log_directory(): void
    {
        $this->createLogFile(['.gitignore']);

        $this->artisan('dainsys:laravel-logs --clear --keep=0');

        $this->assertFileExists($this->logDirectory . '/.gitignore');
    }

    /** @test */
    public function it_shoud_keep_files_with_different_names(): void
    {
        $this->createLogFile(['wrong-name-1.log', 'wrong-name-2.log']);

        $this->assertFileExists($this->logDirectory . '/wrong-name-1.log');
        $this->assertFileExists($this->logDirectory . '/wrong-name-2.log');

        $this->artisan('dainsys:laravel-logs --clear --keep=0');
    }

    /** @test */
    public function it_should_keep_the_last_n_files_if_option_is_provided(): void
    {
        touch($this->logDirectory . '/laravel-1.log', time() - 3600);
        touch($this->logDirectory . '/laravel-2.log', time() - 4600);
        touch($this->logDirectory . '/laravel-3.log', time() - 5600);

        $this->artisan('dainsys:laravel-logs --clear --keep=2');

        $this->assertFileNotExists($this->logDirectory . '/laravel-1.log');
        $this->assertFileExists($this->logDirectory . '/laravel-2.log');
        $this->assertFileExists($this->logDirectory . '/laravel-3.log');
    }

    /** @test */
    public function it_works_with_different_file_name(): void
    {
        touch($this->logDirectory . '/dainsys-log-1.log', time() - 3600);

        $this->artisan('dainsys:laravel-logs dainsys-log- --clear --keep=0');

        $this->assertFileNotExists($this->logDirectory . '/dainsys-log-1.log');
    }

    /** @test */
    public function it_uses_file_name_from_config(): void
    {
        $filename = config('dainsys_clearlogs.prefix');

        touch($this->logDirectory .  "/{$filename}1.log", time() - 3600);

        $this->artisan('dainsys:laravel-logs --clear --keep=0');

        $this->assertFileNotExists($this->logDirectory . "/{$filename}1.log");
    }

    /** @test */
    public function it_return_an_array_with_filenames_if_clear_option_is_not_provided(): void
    {
        $this->createLogFile(['laravel-1.log', 'laravel-2.log', 'laravel-3.log']);

        $this->artisan('dainsys:laravel-logs --clear --keep=0');

        $imploded = implode('","', ["laravel-1.log", "laravel-2.log", "laravel-3.log"]);

        $this
            ->artisan('dainsys:laravel-logs')
            // ->expectsOutput('["' . $imploded . '"]')
            ->assertExitCode(0);
    }
}
