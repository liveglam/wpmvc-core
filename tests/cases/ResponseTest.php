<?php

use WPMVC\Response;

/**
 * Tests response class.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 3.0.0
 */
class ResponseTest extends PHPUnit_Framework_TestCase
{
    function testDefaultFalse()
    {
        // Prepare
        $response = new Response;

        // Assert
        $this->assertFalse($response->success);
        $this->assertTrue($response->passes);
    }

    function testDefaultTrue()
    {
        // Prepare
        $response = new Response(true);

        // Assert
        $this->assertTrue($response->success);
        $this->assertTrue($response->passes);
    }

    function testError()
    {
        // Prepare
        $response = new Response;

        // Execute
        $response->error('field', 'Error');

        // Assert
        $this->assertTrue($response->fails);
        $this->assertFalse($response->passes);
        $this->assertArrayHasKey('field', $response->errors);
        $this->assertInternalType('array', $response->errors['field']);
        $this->assertEquals('Error', $response->errors['field'][0]);
    }

    function testCastingFail()
    {
        // Prepare
        $response = new Response;
        $response->message = 'An error';
        $response->error('field', 'Error');

        // Execute
        $r = $response->to_array();

        // Assert
        $this->assertInternalType('array', $r);
        $this->assertArrayHasKey('error', $r);
        $this->assertArrayHasKey('errors', $r);
        $this->assertArrayHasKey('status', $r);
        $this->assertArrayHasKey('message', $r);
        $this->assertTrue($r['error']);
        $this->assertArrayHasKey('field', $r['errors']);
        $this->assertEquals($response->message, $r['message']);
        $this->assertEquals(500, $r['status']);
    }

    function testCastingSuccess()
    {
        // Prepare
        $response = new Response;
        $response->message = 'An error';
        $response->success = true;

        // Execute
        $r = $response->to_array();

        // Assert
        $this->assertInternalType('array', $r);
        $this->assertArrayHasKey('error', $r);
        $this->assertArrayHasKey('status', $r);
        $this->assertArrayHasKey('message', $r);
        $this->assertFalse($r['error']);
        $this->assertEquals($response->message, $r['message']);
        $this->assertEquals(200, $r['status']);
    }
}