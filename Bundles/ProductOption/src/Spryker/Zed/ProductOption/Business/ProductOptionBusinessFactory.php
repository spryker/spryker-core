<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator\ItemProductOptionGrossPrice;
use Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator\SubtotalWithProductOptions;
use Spryker\Zed\ProductOption\Business\Model\ProductOptionGroupSaver;
use Spryker\Zed\ProductOption\Business\Model\ProductOptionOrderSaver;
use Spryker\Zed\ProductOption\Business\Model\ProductOptionValueSaver;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductOption\Business\Model\ProductOptionGroupSaver
     */
    public function createProductOptionGroupSaver()
    {
       return new ProductOptionGroupSaver($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Model\ProductOptionValueSaver
     */
    public function createProductOptionValueSaver()
    {
        return new ProductOptionValueSaver();
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator\ItemProductOptionGrossPrice
     */
    public function createItemProductOptionGrossPriceAggregator()
    {
        return new ItemProductOptionGrossPrice($this->getSalesQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Model\ProductOptionOrderSaverInterface
     */
    public function createProductOptionOrderSaver()
    {
        return new ProductOptionOrderSaver();
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator\SubtotalWithProductOptions
     */
    public function createSubtotalWithProductOption()
    {
        return new SubtotalWithProductOptions();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_PRODUCT);
    }

}
