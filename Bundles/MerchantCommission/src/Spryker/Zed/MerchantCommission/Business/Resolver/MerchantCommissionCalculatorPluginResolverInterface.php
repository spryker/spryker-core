<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Resolver;

use Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface;

interface MerchantCommissionCalculatorPluginResolverInterface
{
    /**
     * @param string $calculatorTypePlugin
     *
     * @throws \Spryker\Zed\MerchantCommission\Business\Exception\MerchantCommissionCalculatorNotFoundException
     *
     * @return \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface
     */
    public function getMerchantCommissionCalculatorPlugin(string $calculatorTypePlugin): MerchantCommissionCalculatorPluginInterface;
}
