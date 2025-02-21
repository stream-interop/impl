<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use LogicException;
use RuntimeException;
use StreamInterop\Interface\ClosableStream;
use StreamInterop\Interface\ReadableStream;
use StreamInterop\Interface\SeekableStream;
use StreamInterop\Interface\SizableStream;
use StreamInterop\Interface\StreamTypeAliases;
use StreamInterop\Interface\StringableStream;
use StreamInterop\Interface\WritableStream;
use Stringable;

/**
 * Implements no-op on all affordances except ResourceStream.
 */
final class NullStream extends FileStream implements
    ClosableStream,
    ReadableStream,
    SeekableStream,
    SizableStream,
    StringableStream,
    WritableStream
{
    /**
     * @inheridoc
     */
    public array $metadata {
        get {
            if ($this->isOpen()) {
                return [
                    'timed_out' => false,
                    'blocked' => false,
                    'eof' => $this->eof(),
                    'unread_bytes' => 0,
                    'stream_type' => self::class,
                    'wrapper_type' => '',
                    'wrapper_data' => null,
                    'mode' => 'rb+',
                    'seekable' => true,
                ];
            }

            return [];
        }
    }

    private bool $open = true;

    public function __construct()
    {
    }

    /**
     * @inheritdoc
     */
    public function __toString() : string
    {
        $this->assertIsOpen(__FUNCTION__);
        return '';
    }

    /**
     * @inheritdoc
     */
    public function close() : void
    {
        $this->open = false;
    }

    /**
     * @inheritdoc
     */
    public function getContents() : string
    {
        $this->assertIsOpen(__FUNCTION__);
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getSize() : ?int
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function eof() : bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isClosed() : bool
    {
        return ! $this->isOpen();
    }

    /**
     * @inheritdoc
     */
    public function isOpen() : bool
    {
        return $this->open;
    }

    /**
     * @inheritdoc
     */
    public function read(int $length) : string
    {
        $this->assertIsOpen(__FUNCTION__);
        return '';
    }

    /**
     * @inheritdoc
     */
    public function rewind() : void
    {
        $this->assertIsOpen(__FUNCTION__);
    }

    /**
     * @inheritdoc
     */
    public function seek(int $offset, int $whence = SEEK_SET) : void
    {
        $this->assertIsOpen(__FUNCTION__);
    }

    /**
     * @inheritdoc
     */
    public function tell() : int
    {
        $this->assertIsOpen(__FUNCTION__);
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function write(string|Stringable $data) : int
    {
        $this->assertIsOpen(__FUNCTION__);
        return 0;
    }
}
