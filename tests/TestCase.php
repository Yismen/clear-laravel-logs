<?php

namespace Dainsys\Commands\ClearLogs\Tests;

use Dainsys\Commands\ClearLogs\ClearLogsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * The log directory path.
     *
     * @var string
     */
    protected $logDirectory;

    /**
     * Executed before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->logDirectory = storage_path('logs');

        $this->deleteLogFiles();
    }

    /**
     * Executed after each test.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->deleteLogFiles();
    }

    /**
     * Load the command service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [ClearLogsServiceProvider::class];
    }

    /**
     * Create fake log files in the test temporary directory.
     *
     * @param array|string $files
     */
    protected function createLogFile($files): void
    {
        foreach ((array) $files as $file) {
            touch($this->logDirectory . '/' . $file);
        }
    }

    /**
     * Delete all fake log files int the test temporary directory.
     */
    private function deleteLogFiles(): void
    {
        foreach (glob($this->logDirectory . '/*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
