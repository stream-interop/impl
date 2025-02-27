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
        $initial = $this->tell();
        $this->rewind();
        $string = $this->getContents();
        $this->seek($initial);
        return $string;
    }

    /**
     * @inheritdoc
     */
    public function subString(int $offset, ?int $length = null) : string
    {
        $this->assertIsOpen(__FUNCTION__);
        $initial = $this->tell();

        if ($offset < 0) {
            $this->seek($offset, SEEK_END);
        } else {
            $this->seek($offset);
        }

        $string = $this->stringOrThrow(
            stream_get_contents($this->resource, $length),
            __FUNCTION__,
        );

        $this->seek($initial);
        return $string;
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
