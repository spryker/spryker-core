<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraints\LessThan;

class IntegerMoneyConstraint extends LessThan
{
    protected const MAX_INT_VALUE = 2147483647;
    protected const OPTION_VALUE = 'value';

    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        $this->message = sprintf('This value should be less than %d.', (int)(static::MAX_INT_VALUE / 100));
        if ($options === null) {
            $options = [];
        }

        $options[static::OPTION_VALUE] = static::MAX_INT_VALUE;

        parent::__construct($options);
    }
}
