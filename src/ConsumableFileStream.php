<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use StreamInterop\Interface\ReadableStream;

/**
 * A read-only stream to consume the resource without seeking or writing;
 * good for remote streams.
 */
class ConsumableFileStream extends FileStream implements ReadableStream
{
    protected function setResource(mixed $resource) : void
    {
        parent::setResource($resource);
        $this->assertReadable();
    }

    /**
     * @inheritdoc
     */
    public function eof() : bool
    {
        $this->assertIsOpen(__FUNCTION__);
        return feof($this->resource);
    }

    /**
     * @inheritdoc
     */
    public function getContents() : string
    {
        $this->assertIsOpen(__FUNCTION__);

        return $this->stringOrThrow(
            stream_get_contents($this->resource),
            __FUNCTION__,
        );
    }

    /**
     * @inheritdoc
     */
    public function read(int $length) : string
    {
        $this->assertIsOpen(__FUNCTION__);

        return $this->stringOrThrow(
            fread($this->resource, $length),
            __FUNCTION__,
        );
    }
}
