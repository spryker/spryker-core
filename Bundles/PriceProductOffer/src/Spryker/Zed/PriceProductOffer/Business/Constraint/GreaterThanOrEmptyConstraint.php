<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Constraint;

use Symfony\Component\Validator\Constraints\AbstractComparison;

class GreaterThanOrEmptyConstraint extends AbstractComparison
{
    /**
     * @var string
     */
    public $message = 'This value should be greater than {{ compared_value }} or empty.';

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
