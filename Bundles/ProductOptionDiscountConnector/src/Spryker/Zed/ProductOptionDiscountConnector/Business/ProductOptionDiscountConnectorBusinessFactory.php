<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\DiscountTotalAmount;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\ItemProductOptionTaxWithDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\OrderTaxAmountWithDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\ProductOptionDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\ProductOptionDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOptionDiscountConnector\ProductOptionDiscountConnectorConfig getConfig()
 */
class ProductOptionDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\DiscountTotalAmount
     */
    public function createDiscountTotalAmountAggregator()
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
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\ProductOptionDiscounts
     */
    public function createProductOptionDiscountAggregator()
    {
        return new ProductOptionDiscounts(
            $this->getProvidedDependency(ProductOptionDiscountConnectorDependencyProvider::QUERY_CONTAINER_DISCOUNT)
        );
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\ItemProductOptionTaxWithDiscounts
     */
    public function createItemProductOptionsAndDiscountsTaxCalculator()
    {
        return new ItemProductOptionTaxWithDiscounts(
            $this->getProvidedDependency(ProductOptionDiscountConnectorDependencyProvider::FACADE_TAX)
        );
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\OrderTaxAmountWithDiscounts
     */
    public function createOrderTotalWithDiscountsTaxCalculator()
    {
        return new OrderTaxAmountWithDiscounts();
    }
}
