<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use StreamInterop\Interface\AppendableStream;
use StreamInterop\Interface\ResourceStream;
use StreamInterop\Interface\WritableStream;
use Stringable;

/**
 * A read+write+append file stream.
 */
class ReadWriteFileStream extends ReadableFileStream implements AppendableStream, ResourceStream, WritableStream
{
    /**
     * @var resource
     */
    public protected(set) mixed $resource;

    protected function setResource(mixed $resource) : void
    {
        parent::setResource($resource);
        $this->assertWritable();
    }

    /**
     * @inheritdoc
     */
    public function append(string|Stringable $data) : int
    {
        $this->assertIsOpen(__FUNCTION__);

        $this->seek(0, SEEK_END);

        return $this->intOrThrow(
            fwrite($this->resource, (string) $data),
            __FUNCTION__,
        );
    }

    /**
     * @inheritdoc
     */
    public function write(string|Stringable $data) : int
    {
        $this->assertIsOpen(__FUNCTION__);

        return $this->intOrThrow(
            fwrite($this->resource, (string) $data),
            __FUNCTION__,
        );
    }
}
