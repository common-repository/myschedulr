<?php

namespace MySchedulr\Exceptions;

use Exception;

class ApiConnectionException extends Exception
{
    protected $message = 'MySchedulr API connection error.';
}
