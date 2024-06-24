<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Resolver;

use Spryker\Zed\MerchantCommission\Business\Exception\MerchantCommissionCalculatorNotFoundException;
use Spryker\Zed\MerchantCommission\MerchantCommissionDependencyProvider;
use Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface;

class MerchantCommissionCalculatorPluginResolver implements MerchantCommissionCalculatorPluginResolverInterface
{
    /**
     * @var list<\Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface>
     */
    protected array $merchantCommissionCalculatorPlugins;

    /**
     * @param list<\Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface> $merchantCommissionCalculatorPlugins
     */
    public function __construct(array $merchantCommissionCalculatorPlugins)
    {
        $this->merchantCommissionCalculatorPlugins = $merchantCommissionCalculatorPlugins;
    }

    /**
     * @param string $calculatorTypePlugin
     *
     * @throws \Spryker\Zed\MerchantCommission\Business\Exception\MerchantCommissionCalculatorNotFoundException
     *
     * @return \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface
     */
    public function getMerchantCommissionCalculatorPlugin(string $calculatorTypePlugin): MerchantCommissionCalculatorPluginInterface
    {
        foreach ($this->merchantCommissionCalculatorPlugins as $merchantCommissionCalculatorPlugin) {
            if (strcasecmp($merchantCommissionCalculatorPlugin->getCalculatorType(), $calculatorTypePlugin) === 0) {
                return $merchantCommissionCalculatorPlugin;
            }
        }

        throw new MerchantCommissionCalculatorNotFoundException(sprintf(
            'Calculator plugin with type "%s" not found. You can fix this error by adding it to %s::getMerchantCommissionCalculatorPlugins()',
            $calculatorTypePlugin,
            MerchantCommissionDependencyProvider::class,
        ));
    }
}
