<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

interface DiscountCalculatorPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param mixed $percentage
     *
     * @return int
     */
    public function calculate(array $discountableItems, $percentage);

    /**
     * @param int $value
     *
     * @return float
     */
    public function transformForPersistence($value);

    /**
     * @param int $value
     *
     * @return float
     */
    public function transformFromPersistence($value);

    /**
     * @param int $amount
     *
     * @return string
     */
    public function getFormattedAmount($amount);

    /**
     * @return \Symfony\Component\Validator\ValidatorInterface
     */
    public function getAmountValidators();

}
