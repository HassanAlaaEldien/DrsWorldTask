<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Custom assertion for validation error.
     *
     * @param TestResponse $response
     * @param string $field
     */
    public function assertValidationError(TestResponse $response, string $field)
    {
        $response->assertStatus(422);

        // The returned json should contain the validation error on the given field
        $errorMessages = $response->decodeResponseJson()['error']['message'];

        $this->assertArrayHasKey($field, $errorMessages);
    }
}
