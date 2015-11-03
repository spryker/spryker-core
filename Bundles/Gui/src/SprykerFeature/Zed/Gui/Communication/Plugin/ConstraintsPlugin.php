<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConstraintsPlugin extends AbstractPlugin
{
    public function createConstraintNotBlank()
    {
       return new NotBlank();
    }
}
