<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use StreamInterop\Interface\ResourceStream;
use StreamInterop\Interface\WritableStream;
use Stringable;

/**
 * A read+write file stream.
 */
class ReadWriteFileStream extends ReadableFileStream implements ResourceStream, WritableStream
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
    public function write(string|Stringable $data) : int
    {
        return $this->intOrThrow(
            fwrite($this->resource, (string) $data),
            "Could not write to stream.",
        );
    }
}
