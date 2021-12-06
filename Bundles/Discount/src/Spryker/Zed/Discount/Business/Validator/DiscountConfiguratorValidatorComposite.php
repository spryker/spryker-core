<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Validator;

use Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;

class DiscountConfiguratorValidatorComposite implements DiscountConfiguratorValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface>
     */
    protected $discountConfiguratorValidators;

    /**
     * @param array<\Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface> $discountConfiguratorValidators
     */
    public function __construct(array $discountConfiguratorValidators)
    {
        $this->discountConfiguratorValidators = $discountConfiguratorValidators;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer
     */
    public function validateDiscountConfigurator(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
    ): DiscountConfiguratorResponseTransfer {
        $discountConfiguratorResponseTransfer->setIsSuccessful(true);

        foreach ($this->discountConfiguratorValidators as $discountConfiguratorValidator) {
            $discountConfiguratorResponseTransfer = $discountConfiguratorValidator->validateDiscountConfigurator(
                $discountConfiguratorTransfer,
                $discountConfiguratorResponseTransfer,
            );
        }

        return $discountConfiguratorResponseTransfer;
    }
}
