<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeManager;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeSaver;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeTranslator;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeValueSaver;
use Spryker\Zed\ProductManagement\Business\Product\ProductManager;
use Spryker\Zed\ProductManagement\ProductManagementDependencyProvider;

/**
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class ProductManagementBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Product\ProductManagerInterface
     */
    public function createProductManager()
    {
        return new ProductManager(
            $this->getProductAttributeManager(),
            $this->getProductQueryContainer(),
            $this->getStockQueryContainer(),
            $this->getTouchFacade(),
            $this->getUrlFacade(),
            $this->getLocaleFacade(),
            $this->getPriceFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface
     */
    public function getProductAttributeManager()
    {
        return $this->getProductFacade()->getAttributeManager();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToUrlInterface
     */
    protected function getUrlFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    protected function getStockQueryContainer()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::QUERY_CONTAINER_STOCK);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeTranslatorInterface
     */
    public function createAttributeTranslator()
    {
        return new AttributeTranslator(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getLocaleFacade()->getCurrentLocale();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeManagerInterface
     */
    public function createAttributeManager()
    {
        return new AttributeManager(
            $this->getQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeSaverInterface
     */
    public function createAttributeSaver()
    {
        return new AttributeSaver(
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getGlossaryFacade(),
            $this->createAttributeValueSaver()
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeValueSaverInterface
     */
    protected function createAttributeValueSaver()
    {
        return new AttributeValueSaver(
            $this->getQueryContainer()
        );
    }

}
