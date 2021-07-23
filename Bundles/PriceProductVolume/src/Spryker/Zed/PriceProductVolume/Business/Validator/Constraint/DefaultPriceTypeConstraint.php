<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\Validator\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class DefaultPriceTypeConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'Volume prices can only have DEFAULT prices.';

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
