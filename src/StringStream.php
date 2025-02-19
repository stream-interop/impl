<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use RuntimeException;
use StreamInterop\Interface\ReadableStream;
use StreamInterop\Interface\SeekableStream;
use StreamInterop\Interface\SizableStream;
use StreamInterop\Interface\WritableStream;
use Stringable;

/**
 * A read+seek+write stream backed by a string of data instead of a resource.
 */
final class StringStream implements ReadableStream, SeekableStream, SizableStream, WritableStream
{
    /**
     * @inheridoc
     */
    public array $metadata {
        get {
            return [
                'stream_type' => self::CLASS,
                'mode' => 'rb+',
                'unread_bytes' => $this->getSize() - $this->tell(),
                'seekable' => true,
            ];
        }
    }

    private int $position = 0;

    public function __construct(protected string $data = '')
    {
    }

    /**
     * @inheritdoc
     */
    public function read(int $length) : string
    {
        $buffer = substr($this->data, $this->position, $length);
        $this->position += ($length >= strlen($buffer) ? strlen($buffer) : $length);
        return $buffer;
    }

    /**
     * @inheritdoc
     */
    public function write(string|Stringable $data) : int
    {
        $length = strlen((string) $data);
        $this->data .= $data;
        $this->position += $length;
        return $length;
    }

    /**
     * @inheritdoc
     */
    public function eof() : bool
    {
        return $this->position >= strlen($this->data);
    }

    /**
     * @inheritdoc
     * @phpstan-ignore return.unusedType
     */
    public function getSize() : ?int
    {
        return strlen($this->data);
    }

    /**
     * @inheritdoc
     */
    public function seek(int $offset, int $whence = SEEK_SET) : void
    {
        switch ($whence) {
            case SEEK_SET:
                $this->position = $offset;
                break;

            case SEEK_CUR:
                $this->position += $offset;
                break;

            case SEEK_END:
                $this->position = strlen($this->data) + $offset;
                break;

            default:
                throw new RuntimeException("Invalid seek operation: {$whence}");
        }
    }

    /**
     * @inheritdoc
     */
    public function tell() : int
    {
        return $this->position;
    }

    /**
     * @inheritdoc
     */
    public function rewind() : void
    {
        $this->position = 0;
    }

    /**
     * @inheritdoc
     */
    public function getContents() : string
    {
        $data = '';

        while (! $this->eof()) {
            $data .= $this->read(512);
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function isOpen() : bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isClosed() : bool
    {
        return false;
    }
}
