<?php

namespace ApiBundle\Base\Exception;

use Exception;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RestException extends HttpException
{
    /**
     * HTTP code
     *
     * @var int
     * @Serializer\Type("integer")
     */
    private $statusCode;

    /**
     * Error message
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * Present only in debug mode
     *
     * @var string[]
     * @Serializer\Type("array<string>")
     */
    protected $trace;

    /**
     * Present only in debug mode
     *
     * @var Exception
     * @Serializer\Type("Exception")
     */
    protected $previous;

    /**
     * RestException constructor.
     * @param int $statusCode
     * @param string|null $message
     * @param Exception|null $previous
     */
    public function __construct(int $statusCode, string $message = null, Exception $previous = null)
    {
        parent::__construct($statusCode, $message, $previous, array(), 0);
        $this->statusCode = $statusCode;
    }

}