<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business;

use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\ProductOptionDiscountCalculator\DiscountTotalAmount;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\ProductOptionDiscountCalculator\ProductOptionDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\ItemProductOptionTaxWithDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\OrderTaxAmountWithDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\ProductOptionDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOptionDiscountConnector\ProductOptionDiscountConnectorConfig getConfig()
 */
class ProductOptionDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\ProductOptionDiscountCalculator\DiscountTotalAmount
     */
    public function createDiscountTotalWithProductOptionsCalculator()
    {
        return new DiscountTotalAmount();
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderDiscounts
     */
    public function createOrderDiscountAggregator()
    {
        return new OrderDiscounts();
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\ProductOptionDiscountCalculator\ProductOptionDiscounts
     */
    public function createProductOptionDiscountCalculator()
    {
        return new ProductOptionDiscounts(
            $this->getDiscountQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\ItemProductOptionTaxWithDiscounts
     */
    public function createItemProductOptionsAndDiscountsTaxCalculator()
    {
        return new ItemProductOptionTaxWithDiscounts(
            $this->getTaxFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\OrderTaxAmountWithDiscounts
     */
    public function createOrderTotalWithDiscountsTaxCalculator()
    {
        return new OrderTaxAmountWithDiscounts();
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface
     */
    protected function getTaxFacade()
    {
        return $this->getProvidedDependency(ProductOptionDiscountConnectorDependencyProvider::FACADE_TAX);
    }

    /**
     * @return DiscountQueryContainerInterface
     */
    protected function getDiscountQueryContainer()
    {
        return $this->getProvidedDependency(ProductOptionDiscountConnectorDependencyProvider::QUERY_CONTAINER_DISCOUNT);
    }

}
