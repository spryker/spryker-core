<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;
use Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface;
use Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Service\PriceProduct\Model\PriceProductCriteriaBuilder;
use Spryker\Service\PriceProduct\Model\PriceProductCriteriaBuilderInterface;
use Spryker\Service\PriceProduct\Model\PriceProductMatcher;
use Spryker\Service\PriceProduct\Model\PriceProductMatcherInterface;

class PriceProductServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\PriceProduct\Model\PriceProductMatcherInterface
     */
    public function createPriceProductMatcher(): PriceProductMatcherInterface
    {
        return new PriceProductMatcher($this->createProductCriteriaBuilder(), $this->getPriceProductDecisionPlugins());
    }

    /**
     * @return \Spryker\Service\PriceProduct\Model\PriceProductCriteriaBuilderInterface
     */
    public function createProductCriteriaBuilder(): PriceProductCriteriaBuilderInterface
    {
        return new PriceProductCriteriaBuilder(
            $this->getCurrencyFacade(),
            $this->getPriceFacade(),
            $this->getStoreFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface[]
     */
    public function getPriceProductDecisionPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_PRODUCT_DECISION);
    }

    /**
     * @return \Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface
     */
    protected function getCurrencyFacade(): PriceProductToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface
     */
    protected function getPriceFacade(): PriceProductToPriceFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface
     */
    protected function getStoreFacade(): PriceProductToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_STORE);
    }
}
