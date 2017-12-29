<?php

namespace ApiBundle\Base\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends RestException
{
    /**
     * ValidationException constructor.
     * @param ConstraintViolationListInterface $errors
     * @param Exception|null $previous
     */
    public function __construct(
        ConstraintViolationListInterface $errors,
        Exception $previous = null
    ) {
        $message = $this->getValidationErrorsMessage($errors);
        parent::__construct(400, $message, $previous);
    }

    private function getValidationErrorsMessage(ConstraintViolationListInterface $errors)
    {
        $errorMessages = [];
        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $errorMessages[] =  $error->getPropertyPath() . ' - ' . $error->getMessage();
        }

        return implode(' | ', $errorMessages);
    }
}
