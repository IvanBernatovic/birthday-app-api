<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

trait RefreshAndSeedDatabase
{
    use RefreshDatabase;

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function refreshDatabase()
    {
        $this->usingInMemoryDatabase()
        ? $this->refreshInMemoryDatabase()
        : $this->refreshTestDatabase();

        $this->seed();
    }
}
