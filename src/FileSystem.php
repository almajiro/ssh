<?php

namespace Almajiro\Ssh;

class FileSystem
{
    private $connection;
    private $scpConnection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection->getConnection();
        $this->scpConnection = ssh2_sftp($this->connection);
    }

    public function makeDirectory(string $path, int $mode = 0755, bool $recursive = false)
    {
        return ssh2_sftp_mkdir($this->scpConnection, $path, $mode, $recursive);
    }

    public function removeDirectory(string $path)
    {
        return ssh2_sftp_rmdir($this->scpConnection, $path);
    }

    public function putFile(string $localFile, string $remoteFile, int $mode = 0644)
    {
        return ssh2_scp_send($this->connection, $localFile, $remoteFile, $mode);
    }

    public function removeFile(string $path)
    {
        return ssh2_sftp_unlink($this->scpConnection, $path);
    }

    public function changeMode(int $mode, string $path)
    {
        return ssh2_sftp_chmod($this->scpConnection, $path, $mode);
    }

    public function getFilePerms(string $path)
    {
        return fileperms($this->getStreamPath($path)) & 0777;
    }

    public function getFileInfo(string $path)
    {
        return ssh2_sftp_stat($this->scpConnection, $path);
    }

    public function isExists(string $path)
    {
        return file_exists($this->getStreamPath($path));
    }

    private function getStreamPath(string $path)
    {
        return 'ssh2.sftp://'.intval($this->scpConnection).$path;
    }
}
