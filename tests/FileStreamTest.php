<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use LogicException;

class FileStreamTest extends TestCase
{
    protected function newFileStream() : FileStream
    {
        $resource = $this->fopenFakeFile('r');
        assert(is_resource($resource));
        return new FileStream($resource);
    }

    public function testResourceNotOpen() : void
    {
        $resource = $this->fopenFakeFile('r');
        fclose($resource);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Expected resource (stream), got resource (closed).');
        $stream = new FileStream($resource);
    }

    public function testResourceNotValid() : void
    {
        $resource = stream_context_create();
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Expected resource (stream), got resource (stream-context).');
        $stream = new FileStream($resource);
    }

    public function testGetSize() : void
    {
        $expect = filesize($this->fakeFile());
        $actual = $this->newFileStream()->getSize();
        $this->assertSame($expect, $actual);
    }
}
