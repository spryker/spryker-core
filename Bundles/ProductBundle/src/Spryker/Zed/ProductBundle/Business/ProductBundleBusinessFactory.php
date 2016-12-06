<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityCheck;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartItemGroupKeyExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculation;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundleSalesOrderSaver;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleWriter;
use Spryker\Zed\ProductBundle\ProductBundleDependencyProvider;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartPostSaveUpdate;

/**
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class ProductBundleBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleWriter
     */
    public function createProductBundleWriter()
    {
        return new ProductBundleWriter($this->getProductFacade(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander
     */
    public function createProductBundleCartExpander()
    {
        return new ProductBundleCartExpander(
            $this->getQueryContainer(),
            $this->getPriceFacade(),
            $this->getProductFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartItemGroupKeyExpander
     */
    public function createProductBundleCartItemGroupKeyExpander()
    {
        return new ProductBundleCartItemGroupKeyExpander();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundleSalesOrderSaver
     */
    public function createProductBundleSalesOrderSaver()
    {
        return new ProductBundleSalesOrderSaver();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculation
     */
    public function createProductBundlePriceCalculator()
    {
        return new ProductBundlePriceCalculation();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartPostSaveUpdate
     */
    public function createProductBundlePostSaveUpdate()
    {
        return new ProductBundleCartPostSaveUpdate();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityCheck
     */
    public function createProductBundleCartPreCheck()
    {
        return new ProductBundleAvailabilityCheck(
            $this->getAvailabilityFacade(),
            $this->getQueryContainer(),
            $this->getAvailabilityQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler
     */
    public function createProductBundleAvailabilityHandler()
    {
        return new ProductBundleAvailabilityHandler(
            $this->getAvailabilityQueryContainer(),
            $this->getAvailabilityFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface
     */
    protected function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected function getAvailabilityQueryContainer()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::QUERY_CONTAINER_AVAILABILITY);
    }
}
