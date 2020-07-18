<?php

namespace Tests;

use Almajiro\Ssh\Connection;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    /** @var Connection */
    private $connection;

    protected function setUp(): void
    {
        $this->connection = new Connection('server', 'root');
        $this->connection->usePassword('password');
        $this->connection->connect();
    }

    public function testGetOutputReturnsCorrectResult()
    {
        $expectedOutput = 'root';
        $command = $this->connection->command('whoami');
        $this->assertSame($expectedOutput, $command->getOutput());
    }

    public function testGetErrorReturnsCorrectResult()
    {
        $error_msg = 'error message';
        $command = $this->connection->command("1>&2 echo \"${error_msg}\"");
        $this->assertSame($error_msg, $command->getError());
    }

    public function testGetCommandReturnsCorrectResult()
    {
        $commandToRun = 'ls -la';
        $command = $this->connection->command($commandToRun);
        $this->assertSame($commandToRun, $command->getCommand());
    }
}
