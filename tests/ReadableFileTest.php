<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use RuntimeException;

class ReadableFileTest extends TestCase
{
    public function newReadableFile() : ReadableFile
    {
        return new ReadableFile($this->fakeFile());
    }

    public function testCannotOpen() : void
    {
        $stream = new ReadableFile('noSuchWrapper://foobar');
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not open rb resource for noSuchWrapper://foobar');
        $stream->read(1);
    }

    public function testClose() : void
    {
        $stream = $this->newReadableFile();
        $stream->close();
        $this->assertTrue($stream->isClosed());
    }
}
