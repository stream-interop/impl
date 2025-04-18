<?php
declare(strict_types=1);

namespace StreamInterop\Impl;

use Exception;
use StreamInterop\Interface\StreamThrowable;

class StreamException extends Exception implements StreamThrowable
{
}
