<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\Validator\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ValidGrossNetPriceConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'Gross Default and/or Net Default price is required for volume price.';

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
