<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoder;
use Spryker\Zed\Product\Business\Attribute\AttributeKeyManager;
use Spryker\Zed\Product\Business\Attribute\AttributeLoader;
use Spryker\Zed\Product\Business\Attribute\AttributeMerger;
use Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertion;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertion;
use Spryker\Zed\Product\Business\Product\Plugin\PluginAbstractManager;
use Spryker\Zed\Product\Business\Product\Plugin\PluginConcreteManager;
use Spryker\Zed\Product\Business\Product\ProductAbstractManager;
use Spryker\Zed\Product\Business\Product\ProductActivator;
use Spryker\Zed\Product\Business\Product\ProductConcreteManager;
use Spryker\Zed\Product\Business\Product\ProductManager;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlGenerator;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManager;
use Spryker\Zed\Product\Business\Product\Variant\AttributePermutationGenerator;
use Spryker\Zed\Product\Business\Product\Variant\VariantGenerator;
use Spryker\Zed\Product\Business\Transfer\ProductTransferMapper;
use Spryker\Zed\Product\ProductDependencyProvider;

/**
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 */
class ProductBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductManagerInterface
     */
    public function createProductManager()
    {
        return new ProductManager(
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
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createProductAbstractAssertion(),
            $this->createPluginAbstractManager(),
            $this->createSkuGenerator(),
            $this->createAttributeEncoder(),
            $this->createProductTransferMapper()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    public function createProductConcreteManager()
    {
        return new ProductConcreteManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createProductAbstractAssertion(),
            $this->createProductConcreteAssertion(),
            $this->createPluginConcreteManager(),
            $this->createAttributeEncoder(),
            $this->createProductTransferMapper()
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
     * @return \Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface
     */
    public function createProductUrlGenerator()
    {
        return new ProductUrlGenerator(
            $this->createProductAbstractManager(),
            $this->getLocaleFacade(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\VariantGeneratorInterface
     */
    public function createProductVariantGenerator()
    {
        return new VariantGenerator(
            $this->getUrlFacade(),
            $this->createSkuGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface
     */
    protected function createSkuGenerator()
    {
        return new SkuGenerator($this->getUtilTextService());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\AttributePermutationGeneratorInterface
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
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    public function createAttributeEncoder()
    {
        return new AttributeEncoder($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface
     */
    public function createProductTransferMapper()
    {
        return new ProductTransferMapper($this->createAttributeEncoder());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeMergerInterface
     */
    public function createAttributeMerger()
    {
        return new AttributeMerger();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeLoaderInterface
     */
    public function createAttributeLoader()
    {
        return new AttributeLoader(
            $this->getQueryContainer(),
            $this->createAttributeMerger(),
            $this->createAttributeEncoder()
        );
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
     * @return \Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface
     */
    protected function getUtilTextService()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::SERVICE_UTIL_ENCODING);
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
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected function getProductAbstractBeforeCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected function getProductAbstractAfterCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface[]
     */
    protected function getProductAbstractReadPlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_READ);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected function getProductAbstractBeforeUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected function getProductAbstractAfterUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[]
     */
    protected function getProductConcreteBeforeCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[]
     */
    protected function getProductConcreteAfterCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface[]
     */
    protected function getProductConcreteReadPlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_READ);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected function getProductConcreteBeforeUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected function getProductConcreteAfterUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE);
    }

}
