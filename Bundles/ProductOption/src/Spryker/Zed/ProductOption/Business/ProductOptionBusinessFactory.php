<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Business;

use Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator\ItemProductOptionGrossPrice;
use Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator\OrderTaxAmountWithProductOptions;
use Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator\ItemProductOptionTax;
use Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator\SubtotalWithProductOptions;
use Spryker\Zed\ProductOption\Business\Model\ProductOptionOrderSaver;
use Spryker\Zed\ProductOption\Business\Model\ProductOptionReader;
use Spryker\Zed\ProductOption\Business\Model\DataImportWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToProductInterface;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;
use Spryker\Zed\ProductOption\ProductOptionConfig;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * @method ProductOptionConfig getConfig()
 * @method ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductOption\Business\Model\DataImportWriterInterface
     */
    public function createDataImportWriterModel()
    {
        return new DataImportWriter($this->getQueryContainer(), $this->getProductFacade(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Model\ProductOptionReaderInterface
     */
    public function createProductOptionReaderModel()
    {
        return new ProductOptionReader($this->getQueryContainer(), $this->getLocaleFacade());
    }

    /**
     * @return ProductOptionOrderSaver
     */
    public function createProductOptionOrderSaver()
    {
        return new ProductOptionOrderSaver();
    }

    /**
     * @return ItemProductOptionGrossPrice
     */
    public function createItemProductOptionGrossPriceAggregator()
    {
        return new ItemProductOptionGrossPrice($this->getSalesQueryContainer());
    }

    /**
     * @return SubtotalWithProductOptions
     */
    public function createSubtotalWithProductOption()
    {
        return new SubtotalWithProductOptions();
    }

    /**
     * @return ItemProductOptionTax
     */
    public function createItemProductOptionTaxAggregator()
    {
        return new ItemProductOptionTax($this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_TAX));
    }

    /**
     * @return OrderTaxAmountWithProductOptions
     */
    public function createOrderTaxAmountAggregator()
    {
        return new OrderTaxAmountWithProductOptions(
            $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_TAX)
        );
    }

    /**
     * @return SalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return ProductOptionToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return ProductOptionToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_PRODUCT);
    }


}
