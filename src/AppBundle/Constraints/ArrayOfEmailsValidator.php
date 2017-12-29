<?php

namespace AppBundle\Constraints;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\Email;

class ArrayOfEmailsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ( false === is_array($value)) {
            $this->context->buildViolation('This should be array of emails.')
                ->addViolation();

            return;
        }

        foreach ($value as $email) {
            $validation = $this->context->getValidator()->validate($email, new Email());

            if (0 !== $validation->count()) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $email)
                    ->setCode(Email::INVALID_FORMAT_ERROR)
                    ->addViolation();
            }
        }
    }

}