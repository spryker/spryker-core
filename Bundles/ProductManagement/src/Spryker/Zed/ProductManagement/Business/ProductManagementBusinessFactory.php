<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business;

use Spryker\Shared\ProductManagement\Code\KeyBuilder\AttributeGlossaryKeyBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeReader;
use Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferMapper;
use Spryker\Zed\ProductManagement\ProductManagementDependencyProvider;

/**
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class ProductManagementBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeReaderInterface
     */
    public function createAttributeReader()
    {
        return new AttributeReader(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->createProductAttributeTransferGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface
     */
    public function getProductAttributeFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
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
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStockInterface
     */
    protected function getStockFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_STOCK);
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
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface
     */
    protected function getProductImageFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getLocaleFacade()->getCurrentLocale();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferMapperInterface
     */
    protected function createProductAttributeTransferGenerator()
    {
        return new ProductAttributeTransferMapper(
            $this->getLocaleFacade(),
            $this->getGlossaryFacade(),
            $this->createAttributeGlossaryKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Shared\ProductManagement\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected function createAttributeGlossaryKeyBuilder()
    {
        return new AttributeGlossaryKeyBuilder();
    }

}
