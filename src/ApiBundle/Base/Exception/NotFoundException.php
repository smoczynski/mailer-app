<?php

namespace ApiBundle\Base\Exception;

use Exception;

class NotFoundException extends RestException
{
    /**
     * NotFoundException constructor.
     * @param string $message
     * @param Exception|null $previous
     */
    public function __construct(string $message = 'Not found', Exception $previous = null)
    {
        parent::__construct(404, $message, $previous);
    }
}