<?php

namespace MySchedulr\Exceptions;

use Exception;

final class BookingNotFoundException extends Exception
{
    protected $message = 'Not bookings found for this site id.';
}
