<?php
namespace Tests\AppBundle\Validator;

use AppBundle\Entity\Email;
use AppBundle\Validator\ArrayOfEmails;
use AppBundle\Validator\ArrayOfEmailsValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ArrayOfEmailsValidatorTest extends ConstraintValidatorTestCase
{
    public function createValidator()
    {
        return new ArrayOfEmailsValidator();
    }

    public function provideTypeNotValid()
    {
        return [
            ['random string'],
            [189797123891333],
            [new Email()],
            [true]
        ];
    }

    /**
     * @dataProvider provideTypeNotValid
     * @param $value
     */
    public function testTypeNotValid($value)
    {
        $this->validator->validate($value, new ArrayOfEmails());
        $this->buildViolation('This should be array of emails.')->assertRaised();
    }
}