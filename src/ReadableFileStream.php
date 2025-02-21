<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use StreamInterop\Interface\ReadableStream;
use StreamInterop\Interface\SeekableStream;
use StreamInterop\Interface\StringableStream;

/**
 * A fully readable (and seekable) file stream.
 */
class ReadableFileStream extends ConsumableFileStream implements SeekableStream, StringableStream
{
    protected function setResource(mixed $resource) : void
    {
        parent::setResource($resource);
        $this->assertSeekable();
    }

    /**
     * @inheritdoc
     */
    public function __toString() : string
    {
        $this->assertIsOpen(__FUNCTION__);
        $this->rewind();
        return (string) $this->getContents();
    }

    /**
     * @inheritdoc
     */
    public function rewind() : void
    {
        $this->assertIsOpen(__FUNCTION__);

        $this->voidOrThrow(
            rewind($this->resource),
            __FUNCTION__,
        );
    }

    /**
     * @inheritdoc
     */
    public function seek(int $offset, int $whence = SEEK_SET) : void
    {
        $this->assertIsOpen(__FUNCTION__);

        $this->voidOrThrow(
            fseek($this->resource, $offset, $whence),
            __FUNCTION__,
        );
    }

    /**
     * @inheritdoc
     */
    public function tell() : int
    {
        return $this->intOrThrow(
            ftell($this->resource),
            __FUNCTION__,
            -1,
        );
    }
}
