<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Discount\Calculator;

use Generated\Shared\Transfer\DiscountCalculationRequestTransfer;
use Generated\Shared\Transfer\DiscountCalculationResponseTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Service\Discount\Exception\DiscountCalculatorPluginNotFoundException;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

class Calculator implements CalculatorInterface
{
    /**
     * @var array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface>
     */
    protected $discountCalculatorPlugins;

    /**
     * @param array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface> $discountCalculatorPlugins
     */
    public function __construct(array $discountCalculatorPlugins)
    {
        $this->discountCalculatorPlugins = $discountCalculatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountCalculationRequestTransfer $discountCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountCalculationResponseTransfer
     */
    public function calculate(
        DiscountCalculationRequestTransfer $discountCalculationRequestTransfer
    ): DiscountCalculationResponseTransfer {
        $discountCalculatorPlugin = $this->getDiscountCalculatorPlugin($discountCalculationRequestTransfer->getDiscount());

        $discountAmount = $discountCalculatorPlugin->calculateDiscount(
            $discountCalculationRequestTransfer->getDiscountableItems()->getArrayCopy(),
            $discountCalculationRequestTransfer->getDiscount(),
        );

        return (new DiscountCalculationResponseTransfer())->setAmount($discountAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @throws \Spryker\Service\Discount\Exception\DiscountCalculatorPluginNotFoundException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    protected function getDiscountCalculatorPlugin(DiscountTransfer $discountTransfer): DiscountCalculatorPluginInterface
    {
        if (!isset($this->discountCalculatorPlugins[$discountTransfer->getCalculatorPlugin()])) {
            throw new DiscountCalculatorPluginNotFoundException(
                sprintf(
                    'Calculator plugin with name "%s" not found. Did you forget to register it in DiscountDependencyProvider::getAvailableCalculatorPlugins()',
                    $discountTransfer->getCalculatorPlugin(),
                ),
            );
        }

        return $this->discountCalculatorPlugins[$discountTransfer->getCalculatorPlugin()];
    }
}
