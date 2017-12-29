<?php

namespace AppBundle\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class ArrayOfEmails extends Constraint
{
    public $message = 'One of emails "{{ value }}" is not valid.';
}