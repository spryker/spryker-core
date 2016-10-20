<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Product\Business\Attribute\AttributeKeyManager;
use Spryker\Zed\Product\Business\Attribute\AttributeManager;
use Spryker\Zed\Product\Business\Product\Plugin\PluginAbstractManager;
use Spryker\Zed\Product\Business\Product\Plugin\PluginConcreteManager;
use Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertion;
use Spryker\Zed\Product\Business\Product\ProductAbstractManager;
use Spryker\Zed\Product\Business\Product\ProductActivator;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertion;
use Spryker\Zed\Product\Business\Product\ProductConcreteManager;
use Spryker\Zed\Product\Business\Product\ProductManager;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlGenerator;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManager;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Business\Product\Variant\AttributePermutationGenerator;
use Spryker\Zed\Product\Business\Product\Variant\VariantGenerator;
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
            $this->createAttributeManager(),
            $this->createProductAbstractManager(),
            $this->createProductConcreteManager(),
            $this->getQueryContainer()
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
            $this->createProductConcreteManager(),
            $this->createProductAbstractAssertion(),
            $this->createPluginAbstractManager(),
            $this->createSkuGenerator()
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
            $this->createProductAbstractAssertion(),
            $this->createProductConcreteAssertion(),
            $this->createPluginConcreteManager()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductActivatorInterface
     */
    public function createProductActivator()
    {
        return new ProductActivator(
            $this->createProductAbstractManager(),
            $this->createProductConcreteManager(),
            $this->createProductUrlManager()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface
     */
    public function createProductUrlManager()
    {
        return new ProductUrlManager(
            $this->getUrlFacade(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->getQueryContainer(),
            $this->createProductUrlGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Url\ProductUrlGenerator
     */
    public function createProductUrlGenerator()
    {
        return new ProductUrlGenerator(
            $this->createProductAbstractManager(),
            $this->getLocaleFacade(),
            $this->getUtilFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\VariantGenerator
     */
    public function createProductVariantGenerator()
    {
        return new VariantGenerator(
            $this->getUrlFacade(),
            $this->createSkuGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuGenerator
     */
    protected function createSkuGenerator()
    {
        return new SkuGenerator($this->getUtilFacade());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\AttributePermutationGenerator
     */
    public function createAttributePermutationGenerator()
    {
        return new AttributePermutationGenerator();
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
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToUtilInterface
     */
    protected function getUtilFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_UTIL);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getLocaleFacade()->getCurrentLocale();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface
     */
    protected function createProductAbstractAssertion()
    {
        return new ProductAbstractAssertion(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface
     */
    protected function createProductConcreteAssertion()
    {
        return new ProductConcreteAssertion(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Plugin\PluginAbstractManagerInterface
     */
    protected function createPluginAbstractManager()
    {
        return new PluginAbstractManager(
            $this->getProductAbstractBeforeCreatePlugins(),
            $this->getProductAbstractAfterCreatePlugins(),
            $this->getProductAbstractReadPlugins(),
            $this->getProductAbstractBeforeUpdatePlugins(),
            $this->getProductAbstractAfterUpdatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Plugin\PluginConcreteManagerInterface
     */
    protected function createPluginConcreteManager()
    {
        return new PluginConcreteManager(
            $this->getProductConcreteBeforeCreatePlugins(),
            $this->getProductConcreteAfterCreatePlugins(),
            $this->getProductConcreteReadPlugins(),
            $this->getProductConcreteBeforeUpdatePlugins(),
            $this->getProductConcreteAfterUpdatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected function getProductAbstractBeforeCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected function getProductAbstractAfterCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected function getProductAbstractReadPlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_READ);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected function getProductAbstractBeforeUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected function getProductAbstractAfterUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected function getProductConcreteBeforeCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected function getProductConcreteAfterCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected function getProductConcreteReadPlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_READ);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected function getProductConcreteBeforeUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected function getProductConcreteAfterUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE);
    }

}
