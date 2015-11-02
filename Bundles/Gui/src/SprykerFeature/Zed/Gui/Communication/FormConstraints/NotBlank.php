<?php

namespace SprykerFeature\Zed\Gui\Communication\FormConstraints;

use Symfony\Component\Validator\Constraints;

class NotBlank extends Constraints\NotBlank
{
    public $message = '-not blank error-';
}
