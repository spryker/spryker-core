<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleAvailabilityCheck;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleCartExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleCartItemGroupKeyExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundlePriceCalculation;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleSalesOrderSaver;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleWriter;
use Spryker\Zed\ProductBundle\ProductBundleDependencyProvider;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundlePostSaveUpdate;

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
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleCartExpander
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
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleCartItemGroupKeyExpander
     */
    public function createProductBundleCartItemGroupKeyExpander()
    {
        return new ProductBundleCartItemGroupKeyExpander();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleSalesOrderSaver
     */
    public function createProductBundleSalesOrderSaver()
    {
        return new ProductBundleSalesOrderSaver();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundlePriceCalculation
     */
    public function createProductBundlePriceCalculator()
    {
        return new ProductBundlePriceCalculation();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundlePostSaveUpdate
     */
    public function createProductBundlePostSaveUpdate()
    {
        return new ProductBundlePostSaveUpdate();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleAvailabilityCheck
     */
    public function createProductBundleCartPreCheck()
    {
        return new ProductBundleAvailabilityCheck($this->getAvailabilityFacade(), $this->getQueryContainer());
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
}
