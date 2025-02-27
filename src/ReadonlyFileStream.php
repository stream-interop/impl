<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use StreamInterop\Interface\ReadonlyStream;

/**
 * A fully readable (and seekable) file stream in readonly mode.
 */
class ReadonlyFileStream extends ReadableFileStream implements ReadonlyStream
{
    /**
     * @param string|resource $resource
     */
    public function __construct(mixed $resource)
    {
        if ($resource === 'php://input') {
            parent::__construct($this->openResource($resource, 'rb'));
            return;
        }

        $copy = $this->openResource('php://memory', 'wb+');

        if (is_resource($resource)) {
            stream_copy_to_stream($resource, $copy);
            rewind($copy);
            parent::__construct($copy);
            return;
        }

        assert(is_string($resource));
        $resource = $this->openResource($resource, 'rb');
        stream_copy_to_stream($resource, $copy);
        fclose($resource);
        rewind($copy);
        parent::__construct($copy);
    }

    /**
     * @return resource
     */
    protected function openResource(string $filename, string $mode) : mixed
    {
        $resource = fopen($filename, $mode);
        assert(is_resource($resource));
        return $resource;
    }
}
