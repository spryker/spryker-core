<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Library\Constraints;

trait ConstraintSerializeTrait
{

    protected $constraint_options;

    public function serialize()
    {
        $className = strtolower((new \ReflectionClass($this))->getShortName());

        $options = $this->constraint_options;

        if (count($options) === 0) {
            $options = true;
        } elseif (count($options) === 1
        && isset($options[$className])) {
            $options = $options[$className];
        }

        return [
            'name' => $className,
            'options' => $options,
        ];
    }

    public function validatedBy()
    {
        $validatorClass = (new \ReflectionClass($this))->getShortName() . 'Validator';

        return 'Symfony\Component\Validator\Constraints\\' . $validatorClass;
    }

}
