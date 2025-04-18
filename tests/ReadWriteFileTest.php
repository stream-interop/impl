<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use StreamInterop\Interface\StreamThrowable;

class ReadWriteFileTest extends TestCase
{
    public function newReadWriteFile() : ReadWriteFile
    {
        return new ReadWriteFile($this->fakeFile());
    }

    public function testCannotOpen() : void
    {
        $stream = new ReadWriteFile('noSuchWrapper://foobar');
        $this->expectException(StreamThrowable::class);
        $this->expectExceptionMessage('Could not open rb+ resource for noSuchWrapper://foobar');
        $stream->read(1);
    }

    public function testClose() : void
    {
        $stream = $this->newReadWriteFile();
        $stream->close();
        $this->assertTrue($stream->isClosed());
    }
}
