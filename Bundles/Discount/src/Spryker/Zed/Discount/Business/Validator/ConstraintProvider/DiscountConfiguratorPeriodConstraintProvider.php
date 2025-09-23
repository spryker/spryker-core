<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Validator\ConstraintProvider;

use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Spryker\Zed\Discount\DiscountConfig;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;

class DiscountConfiguratorPeriodConstraintProvider implements DiscountConfiguratorConstraintProviderInterface
{
    /**
     * @param \Spryker\Zed\Discount\DiscountConfig $discountConfig
     */
    public function __construct(protected DiscountConfig $discountConfig)
    {
    }

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'The discount cannot end before the starting date.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MAX_ALLOWED_DATE = 'Date cannot be later than {{ compared_value }}';

    /**
     * @return array<string, array<\Symfony\Component\Validator\Constraint>>
     */
    public function getConstraints(): array
    {
        return [
            DiscountGeneralTransfer::VALID_FROM => [
                new DateTime(),
                new LessThan([
                    'value' => $this->discountConfig->getMaxAllowedDatetime(),
                    'message' => static::ERROR_MESSAGE_MAX_ALLOWED_DATE,
                ]),
            ],
            DiscountGeneralTransfer::VALID_TO => [
                new DateTime(),
                new GreaterThan([
                    'propertyPath' => DiscountGeneralTransfer::VALID_FROM,
                    'message' => static::ERROR_MESSAGE,
                ]),
                new LessThan([
                    'value' => $this->discountConfig->getMaxAllowedDatetime(),
                    'message' => static::ERROR_MESSAGE_MAX_ALLOWED_DATE,
                ]),
            ],
        ];
    }
}
