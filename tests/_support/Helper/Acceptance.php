<?php
namespace Helper;

use Codeception\Module;

class Acceptance extends Module
{
    public function getKeyboard()
    {
        return $this->getModule('WebDriver')->webDriver->getKeyboard();
    }

}
