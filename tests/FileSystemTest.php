<?php

namespace Tests;

use Almajiro\Ssh\Connection;
use Almajiro\Ssh\FileSystem;
use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase
{
    /** @var FileSystem */
    private $fileSystem;

    /** @var string */
    private $testDirName = '/test_dir';

    protected function setUp(): void
    {
        $connection = new Connection('server', 'root');
        $connection->usePassword('password');
        $connection->connect();

        $this->fileSystem = $connection->filesystem();
    }

    private function getTempFile(string $text = 'This is a test')
    {
        $tempFilePath = tempnam('/tmp', 'test');
        $fp = fopen($tempFilePath, 'w+');
        fwrite($fp, $text);
        fclose($fp);

        return $tempFilePath;
    }

    public function testIsExistsReturnsCorrectState()
    {
        $this->assertTrue($this->fileSystem->isExists('/tmp'));
        $this->assertFalse($this->fileSystem->isExists('/empty_dir'));
    }

    public function testMakeDirectory()
    {
        $this->assertTrue($this->fileSystem->makeDirectory($this->testDirName));
        $this->assertTrue($this->fileSystem->isExists($this->testDirName));
    }

    /**
     * @depends testMakeDirectory
     */
    public function testRemoveDirectory()
    {
        $this->assertTrue($this->fileSystem->removeDirectory($this->testDirName));
        $this->assertFalse($this->fileSystem->isExists($this->testDirName));
    }

    public function testPutFile()
    {
        $tempFilePath = $this->getTempFile();
        $remoteFilePath = '/tmp/testfile';

        $this->assertTrue($this->fileSystem->putFile($tempFilePath, $remoteFilePath));
        $this->assertIsArray($this->fileSystem->getFileInfo($remoteFilePath));

        $this->fileSystem->removeFile($remoteFilePath);
    }

    /**
     * @depends testPutFile
     */
    public function testChangeMode()
    {
        $tempFilePath = $this->getTempFile();
        $remoteFilePath = '/tmp/testfile';
        $mode = 0600;

        $this->fileSystem->putFile($tempFilePath, $remoteFilePath);

        $this->assertTrue($this->fileSystem->changeMode($mode, $remoteFilePath));
        $this->assertEquals($mode, $this->fileSystem->getFilePerms($remoteFilePath));
    }
}
