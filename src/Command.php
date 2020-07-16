<?php

namespace Almajiro\Ssh;

class Command
{
    private $command;
    private $output;
    private $error;

    public function __construct($connection, string $command)
    {
        $this->command = $command;

        $stream = ssh2_exec($connection->getConnection(), $command);

        if ($stream === false) {
            // throw execution failed exception
        }

        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

        $this->output = $this->getStreamOutput($stream);
        $this->error = $this->getStreamOutput($errorStream);
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getOutput()
    {
        return $this->output;
    }

    private function getStreamOutput($stream)
    {
        stream_set_blocking($stream, true);
        $output = trim(stream_get_contents($stream));
        fclose($stream);

        return $output;
    }
}

