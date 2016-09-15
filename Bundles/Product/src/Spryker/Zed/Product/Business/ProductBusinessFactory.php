<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Product\Business\Attribute\AttributeKeyManager;
use Spryker\Zed\Product\Business\Attribute\AttributeManager;
use Spryker\Zed\Product\Business\Product\ProductAbstractAssertion;
use Spryker\Zed\Product\Business\Product\ProductAbstractManager;
use Spryker\Zed\Product\Business\Product\ProductConcreteAssertion;
use Spryker\Zed\Product\Business\Product\ProductConcreteManager;
use Spryker\Zed\Product\Business\Product\ProductManager;
use Spryker\Zed\Product\Business\Product\ProductVariantBuilder;
use Spryker\Zed\Product\ProductDependencyProvider;

/**
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 */
class ProductBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductManager
     */
    protected $productManager;

    /**
     * @return string
     */
    public function getYvesUrl()
    {
        return $this->getConfig()->getHostYves();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface
     */
    public function createAttributeManager()
    {
        return new AttributeManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductManagerInterface
     */
    public function createProductManager()
    {
        return new ProductManager(
            $this->createProductAbstractManager(),
            $this->createProductConcreteManager(),
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getUrlFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    public function createProductAbstractManager()
    {
        return new ProductAbstractManager(
            $this->createAttributeManager(),
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getUrlFacade(),
            $this->getLocaleFacade(),
            $this->getPriceFacade(),
            $this->createProductConcreteManager(),
            $this->createProductAbstractAssertion()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    public function createProductConcreteManager()
    {
        return new ProductConcreteManager(
            $this->createAttributeManager(),
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getUrlFacade(),
            $this->getLocaleFacade(),
            $this->getPriceFacade(),
            $this->createProductAbstractAssertion(),
            $this->createProductConcreteAssertion()
        );
    }

    /**
     * TODO Remove when ZedProductConcreteTransfer is removed
     *
     * @return \Spryker\Zed\Product\Business\Product\ProductVariantBuilder
     */
    public function createProductVariantBuilder()
    {
         return new ProductVariantBuilder($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeKeyManagerInterface
     */
    public function createAttributeKeyManager()
    {
        return new AttributeKeyManager($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected function getUrlFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getLocaleFacade()->getCurrentLocale();
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToPriceInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductAbstractAssertionInterface
     */
    protected function createProductAbstractAssertion()
    {
        return new ProductAbstractAssertion(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductConcreteAssertionInterface
     */
    protected function createProductConcreteAssertion()
    {
        return new ProductConcreteAssertion(
            $this->getQueryContainer()
        );
    }

}
