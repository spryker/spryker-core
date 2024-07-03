<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantCommissionConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Business\CollectorRule\ProductPriceCollectorRule;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Business\CollectorRule\ProductPriceCollectorRuleInterface;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Business\Converter\MoneyValueConverter;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Business\Converter\MoneyValueConverterInterface;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToMoneyFacadeInterface;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToRuleEngineFacadeInterface;
use Spryker\Zed\PriceProductMerchantCommissionConnector\PriceProductMerchantCommissionConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductMerchantCommissionConnector\PriceProductMerchantCommissionConnectorConfig getConfig()
 */
class PriceProductMerchantCommissionConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductMerchantCommissionConnector\Business\CollectorRule\ProductPriceCollectorRuleInterface
     */
    public function createProductPriceCollectorRule(): ProductPriceCollectorRuleInterface
    {
        return new ProductPriceCollectorRule(
            $this->createMoneyValueConverter(),
            $this->getRuleEngineFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantCommissionConnector\Business\Converter\MoneyValueConverterInterface
     */
    public function createMoneyValueConverter(): MoneyValueConverterInterface
    {
        return new MoneyValueConverter($this->getMoneyFacade());
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToMoneyFacadeInterface
     */
    public function getMoneyFacade(): PriceProductMerchantCommissionConnectorToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantCommissionConnectorDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToRuleEngineFacadeInterface
     */
    public function getRuleEngineFacade(): PriceProductMerchantCommissionConnectorToRuleEngineFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantCommissionConnectorDependencyProvider::FACADE_RULE_ENGINE);
    }
}
