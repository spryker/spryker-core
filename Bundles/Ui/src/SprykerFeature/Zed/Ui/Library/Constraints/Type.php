<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Library\Constraints;

class Type extends \Symfony\Component\Validator\Constraints\Type implements SerializeInterface
{

    use ConstraintSerializeTrait;

    public function __construct($options)
    {
        $this->constraint_options = $options;

        parent::__construct($options);
    }

}
