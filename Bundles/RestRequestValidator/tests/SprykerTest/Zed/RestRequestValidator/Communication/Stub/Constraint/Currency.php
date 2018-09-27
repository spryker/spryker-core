<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication\Stub\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class Currency extends SymfonyConstraint
{
    protected const VALID_CURRENCIES = [
        'FJD',
    ];

    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @param string $isoCode
     *
     * @return bool
     */
    public function isValidCurrencyIsoCode(string $isoCode): bool
    {
        return in_array($isoCode, static::VALID_CURRENCIES);
    }
}
