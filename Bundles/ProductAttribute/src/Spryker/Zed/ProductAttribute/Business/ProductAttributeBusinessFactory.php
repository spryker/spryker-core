<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business;

use Spryker\Shared\ProductAttribute\Code\KeyBuilder\AttributeGlossaryKeyBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeReader;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeTranslationReader;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeTranslator;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeValueWriter;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeWriter;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapper;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\SuperAttributeReader;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\SuperAttributeReaderInterface;
use Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapper;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttribute;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReader;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriter;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReader;
use Spryker\Zed\ProductAttribute\Dependency\Service\ProductAttributeToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductAttribute\ProductAttributeDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface getRepository()
 */
class ProductAttributeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeInterface
     */
    public function createProductAttributeManager()
    {
        return new ProductAttribute(
            $this->createProductAttributeReader(),
            $this->createProductAttributeMapper(),
            $this->createProductReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface
     */
    public function createProductAttributeReader()
    {
        return new ProductAttributeReader(
            $this->getQueryContainer(),
            $this->createProductAttributeMapper(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriterInterface
     */
    public function createProductAttributeWriter()
    {
        return new ProductAttributeWriter(
            $this->createProductAttributeReader(),
            $this->getLocaleFacade(),
            $this->getProductFacade(),
            $this->createProductReader(),
            $this->getUtilSanitizeService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface
     */
    public function createProductAttributeMapper()
    {
        return new ProductAttributeMapper(
            $this->getEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeWriterInterface
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
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeReaderInterface
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
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeTranslatorInterface
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
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReaderInterface
     */
    protected function createProductReader()
    {
        return new ProductReader(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeTranslationReaderInterface
     */
    public function createAttributeTranslationReader()
    {
        return new AttributeTranslationReader(
            $this->getGlossaryFacade(),
            $this->createAttributeGlossaryKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Attribute\SuperAttributeReaderInterface
     */
    public function createSuperAttributeReader(): SuperAttributeReaderInterface
    {
        return new SuperAttributeReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeValueWriterInterface
     */
    protected function createAttributeValueWriter()
    {
        return new AttributeValueWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapperInterface
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
     * @return \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected function createAttributeGlossaryKeyBuilder()
    {
        return new AttributeGlossaryKeyBuilder();
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Dependency\Service\ProductAttributeToUtilEncodingInterface
     */
    protected function getEncodingService()
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Dependency\Service\ProductAttributeToUtilSanitizeServiceInterface
     */
    protected function getUtilSanitizeService(): ProductAttributeToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
