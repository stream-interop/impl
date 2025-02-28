<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use LogicException;

class ReadonlyFileStreamTest extends TestCase
{
    /**
     * @var resource
     */
    protected mixed $origin;

    public function testFromResource() : void
    {
        $origin = $this->fopenFakeFile('r');
        $expect = stream_get_contents($origin);
        rewind($origin);
        $stream = new ReadonlyFileStream($origin);
        $this->assertSame($expect, (string) $stream);
    }

    public function testFromPath() : void
    {
        $origin = $this->fakeFile();
        $expect = file_get_contents($origin);
        $stream = new ReadonlyFileStream($origin);
        $this->assertSame($expect, (string) $stream);
    }

    public function testFromPhpInput() : void
    {
        $origin = 'php://input';
        $expect = file_get_contents($origin);
        $stream = new ReadonlyFileStream($origin);
        $this->assertSame($expect, (string) $stream);
    }
}
