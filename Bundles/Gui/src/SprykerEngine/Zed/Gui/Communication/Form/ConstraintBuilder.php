<?php
namespace SprykerEngine\Zed\Gui\Communication\Form;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConstraintBuilder
{
    protected $constraints = null;

    public static function getInstance()
    {
        return new static();
    }

    public function addNotBlank()
    {
        return $this->add(new NotBlank());
    }

    public function add(Constraint $constraint)
    {
        $this->constraints[] = $constraint;
        return $this;
    }

    public function addLength($options)
    {
        return $this->add(new Length($options));
    }

    public function getConstraints()
    {
        return $this->constraints;
    }
}