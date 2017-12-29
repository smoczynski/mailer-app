<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\Email;

abstract class AbstractMailer
{
    const SENDING_SUCCESS = 'success';
    const SENDING_FAILED = 'failed';

    abstract function send(Email $email): string;
}