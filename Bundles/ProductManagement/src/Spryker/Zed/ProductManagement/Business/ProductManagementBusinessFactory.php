<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business;

use Spryker\Shared\ProductManagement\Code\KeyBuilder\AttributeGlossaryKeyBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessor;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeReader;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeTranslator;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeValueWriter;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeWriter;
use Spryker\Zed\ProductManagement\Business\Attribute\Manage\Read;
use Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferMapper;
use Spryker\Zed\ProductManagement\ProductManagementDependencyProvider;

/**
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class ProductManagementBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeTranslatorInterface
     */
    public function createAttributeTranslator()
    {
        return new AttributeTranslator(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->getGlossaryFacade(),
            $this->createAttributeGlossaryKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeWriterInterface
     */
    public function createAttributeWriter()
    {
        return new AttributeWriter(
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getGlossaryFacade(),
            $this->createAttributeValueWriter(),
            $this->createAttributeGlossaryKeyBuilder()
        );
    }

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
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface
     */
    public function createAttributeProcessor()
    {
        return new AttributeProcessor();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\Manage\Read
     */
    public function createReadAttributeManager()
    {
        return new Read($this->createAttributeProcessor());
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
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeValueWriterInterface
     */
    protected function createAttributeValueWriter()
    {
        return new AttributeValueWriter(
            $this->getQueryContainer()
        );
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
