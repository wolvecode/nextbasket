<?php

namespace NextBasket\Utils;

class Log
{
    /**
     * @var false|resource
     */
    protected $handler;

    /**
     * @var string
     */
    protected string $filePath;

    /**
     * @param string $filePath
     * @throws \Exception
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->handler = fopen($filePath, 'a+');
    }

    /**
     * Write a line to the log file.
     *
     * @param string $content
     * @return $this
     */
    public function write(string $content): static
    {
        fwrite($this->handler, $content);

        return $this;
    }

    /**
     * Read the log content as whole or partially
     *
     * @return false|string
     */
    public function read(int $length): bool|string
    {
        return fread($this->handler, $length || filesize($this->filePath));
    }

    /**
     * Close the file handler stream.
     */
    public function __destruct()
    {
        fclose($this->handler);
    }
}
