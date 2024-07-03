<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductMerchantCommissionConnector\Business\CollectorRule\ProductAttributeCollectorRule;
use Spryker\Zed\ProductMerchantCommissionConnector\Business\CollectorRule\ProductAttributeCollectorRuleInterface;
use Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductAttributeReader;
use Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductAttributeReaderInterface;
use Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductReader;
use Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductReaderInterface;
use Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToProductFacadeInterface;
use Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToRuleEngineFacadeInterface;
use Spryker\Zed\ProductMerchantCommissionConnector\ProductMerchantCommissionConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductMerchantCommissionConnector\ProductMerchantCommissionConnectorConfig getConfig()
 */
class ProductMerchantCommissionConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductMerchantCommissionConnector\Business\CollectorRule\ProductAttributeCollectorRuleInterface
     */
    public function createProductAttributeCollectorRule(): ProductAttributeCollectorRuleInterface
    {
        return new ProductAttributeCollectorRule(
            $this->createProductReader(),
            $this->createProductAttributeReader(),
            $this->getRuleEngineFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductAttributeReaderInterface
     */
    public function createProductAttributeReader(): ProductAttributeReaderInterface
    {
        return new ProductAttributeReader($this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductReaderInterface
     */
    public function createProductReader(): ProductReaderInterface
    {
        return new ProductReader($this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToRuleEngineFacadeInterface
     */
    public function getRuleEngineFacade(): ProductMerchantCommissionConnectorToRuleEngineFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantCommissionConnectorDependencyProvider::FACADE_RULE_ENGINE);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToProductFacadeInterface
     */
    public function getProductFacade(): ProductMerchantCommissionConnectorToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantCommissionConnectorDependencyProvider::FACADE_PRODUCT);
    }
}
