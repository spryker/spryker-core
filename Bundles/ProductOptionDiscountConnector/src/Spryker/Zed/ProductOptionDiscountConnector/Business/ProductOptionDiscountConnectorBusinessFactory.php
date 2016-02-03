<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace Spryker\Zed\ProductOptionDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\DiscountTotalAmount;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\ItemProductOptionTaxWithDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderTaxAmountWithDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\ProductOptionDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\ProductOptionDiscountConnectorConfig;
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
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\ItemProductOptionTaxWithDiscounts
     */
    public function createItemProductOptionsAndDiscountsAggregator()
    {
        return new ItemProductOptionTaxWithDiscounts(
            $this->getProvidedDependency(ProductOptionDiscountConnectorDependencyProvider::FACADE_TAX)
        );
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderTaxAmountWithDiscounts
     */
    public function createOrderTaxAmountWithDiscounts()
    {
        return new OrderTaxAmountWithDiscounts(
            $this->getProvidedDependency(ProductOptionDiscountConnectorDependencyProvider::FACADE_TAX)
        );
    }
}
