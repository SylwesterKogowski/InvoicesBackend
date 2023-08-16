<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class IntegrationTestCase extends BaseTestCase
{
    use CreatesApplication;
}
