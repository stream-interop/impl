<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

class ReadWriteFileStreamTest extends TestCase
{
    public function newReadWriteFileStream() : ReadWriteFileStream
    {
        $resource = fopen('php://memory', 'r+');
        assert(is_resource($resource));
        return new ReadWriteFileStream($resource);
    }

    public function testAppend() : void
    {
        $stream = $this->newReadWriteFileStream();
        $stream->append('The quick ');
        $stream->rewind();
        $stream->append('brown fox');
        $expect = 'The quick brown fox';
        $actual = (string) $stream;
        $this->assertSame($expect, $actual);
    }

    public function testReadWrite() : void
    {
        $stream = $this->newReadWriteFileStream();
        $expect = 'The quick brown fox';
        $stream->write($expect);
        $actual = (string) $stream;
        $this->assertSame($expect, $actual);
    }
}
