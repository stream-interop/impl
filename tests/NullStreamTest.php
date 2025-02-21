<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use RuntimeException;

class NullStreamTest extends TestCase
{
    public function test() : void
    {
        $stream = new NullStream();

        // basics
        $this->assertFalse($stream->isClosed());
        $this->assertTrue($stream->isOpen());

        $expect = [
            'timed_out' => false,
            'blocked' => false,
            'eof' => true,
            'unread_bytes' => 0,
            'stream_type' => NullStream::class,
            'wrapper_type' => '',
            'wrapper_data' => null,
            'mode' => 'rb+',
            'seekable' => true,
        ];

        $this->assertSame($expect, $stream->metadata);

        // readable
        $this->assertTrue($stream->eof());
        $this->assertSame('', $stream->getContents());
        $this->assertSame('', $stream->read(1));

        // seekable
        $stream->rewind();
        $stream->seek(88);
        $this->assertSame(0, $stream->tell());

        // sizable
        $this->assertNull($stream->getSize());

        // stringable
        $this->assertSame('', (string) $stream);

        // writable
        $this->assertSame(0, $stream->write('foo bar baz'));

        // close
        $stream->close();
        $this->assertTrue($stream->isClosed());
        $this->assertFalse($stream->isOpen());
        $this->assertSame([], $stream->metadata);

        // fail
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Call to read() failed; stream resource is not open.");
        $stream->read(1);
    }
}
