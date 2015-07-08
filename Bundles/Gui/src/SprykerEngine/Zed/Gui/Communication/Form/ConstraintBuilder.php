<?php
namespace SprykerEngine\Zed\Gui\Communication\Form;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConstraintBuilder
{


    public function addNotBlank()
    {
        return new NotBlank();
    }

    public function addLength($options)
    {
        return new Length($options);
    }

}