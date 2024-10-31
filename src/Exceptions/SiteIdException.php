<?php

namespace MySchedulr\Exceptions;

use Exception;

final class SiteIdException extends Exception
{
    protected $message = 'Invalid Site ID, please relink your account. If this problem persists, please contact support.';
}
