<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Discount;

use Spryker\Service\Discount\Calculator\Calculator;
use Spryker\Service\Discount\Calculator\CalculatorInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryResolver;

class DiscountServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Discount\Calculator\CalculatorInterface
     */
    public function createCalculator(): CalculatorInterface
    {
        return new Calculator($this->getZedCalculatorPlugins());
    }

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface>
     */
    public function getZedCalculatorPlugins(): array
    {
        /** @var \Spryker\Zed\Discount\Business\DiscountBusinessFactory $discountBusinessFactory */
        $discountBusinessFactory = (new BusinessFactoryResolver())->resolve($this);

        return $discountBusinessFactory->getCalculatorPlugins();
    }
}
