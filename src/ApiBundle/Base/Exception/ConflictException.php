<?php

namespace ApiBundle\Base\Exception;

use Exception;

class ConflictException extends RestException
{
    /**
     * ConflictException constructor.
     * @param string $message
     * @param Exception|null $previous
     */
    public function __construct(string $message = 'Conflict', Exception $previous = null)
    {
        parent::__construct(409, $message, $previous);
    }
}
