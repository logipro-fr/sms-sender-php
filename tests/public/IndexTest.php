<?php

namespace Tests\SmsClient;

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testEchoWelcome(): void
    {
        $this->expectOutputString('Welcome to SmsClient!');
        require getcwd() . '/src/public/index.php';
    }
}
