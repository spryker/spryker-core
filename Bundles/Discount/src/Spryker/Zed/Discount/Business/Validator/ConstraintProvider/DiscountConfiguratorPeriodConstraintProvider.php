<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Validator\ConstraintProvider;

use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;

class DiscountConfiguratorPeriodConstraintProvider implements DiscountConfiguratorConstraintProviderInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'The discount cannot end before the starting date.';

    /**
     * @return array<string, array<\Symfony\Component\Validator\Constraint>>
     */
    public function getConstraints(): array
    {
        return [
            DiscountGeneralTransfer::VALID_FROM => [
                new DateTime(),
            ],
            DiscountGeneralTransfer::VALID_TO => [
                new DateTime(),
                new GreaterThan([
                    'propertyPath' => DiscountGeneralTransfer::VALID_FROM,
                    'message' => static::ERROR_MESSAGE,
                ]),
            ],
        ];
    }
}
