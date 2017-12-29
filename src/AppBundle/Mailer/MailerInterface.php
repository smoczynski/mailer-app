<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\Email;

interface MailerInterface
{
    public function send(Email $mail): string;
}