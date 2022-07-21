<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyMarketplacePayment\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class DateOfBirthValueConstraint extends SymfonyConstraint
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'checkout.step.payment.must_be_older_than_18_years';

    /**
     * @var string
     */
    protected const MINIMAL_DATE = '-18 years';

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::ERROR_MESSAGE;
    }

    /**
     * @return string
     */
    public function getMinimalDate(): string
    {
        return static::MINIMAL_DATE;
    }
}
