<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToCurrencyClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Expander\CartItemExpander;
use Spryker\Glue\ProductOptionsRestApi\Processor\Expander\CartItemExpanderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Expander\ProductOptionByProductAbstractSkuExpander;
use Spryker\Glue\ProductOptionsRestApi\Processor\Expander\ProductOptionByProductAbstractSkuExpanderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Expander\ProductOptionByProductConcreteSkuExpander;
use Spryker\Glue\ProductOptionsRestApi\Processor\Expander\ProductOptionByProductConcreteSkuExpanderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapper;
use Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapperInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionStorageReader;
use Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionStorageReaderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\RestResponseBuilder\ProductOptionRestResponseBuilder;
use Spryker\Glue\ProductOptionsRestApi\Processor\RestResponseBuilder\ProductOptionRestResponseBuilderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorter;
use Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Translator\ProductOptionTranslator;
use Spryker\Glue\ProductOptionsRestApi\Processor\Translator\ProductOptionTranslatorInterface;

class ProductOptionsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionStorageReaderInterface
     */
    public function createProductOptionStorageReader(): ProductOptionStorageReaderInterface
    {
        return new ProductOptionStorageReader(
            $this->getProductStorageClient(),
            $this->getProductOptionStorageClient(),
            $this->createProductOptionTranslator(),
            $this->createProductOptionRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Processor\RestResponseBuilder\ProductOptionRestResponseBuilderInterface
     */
    public function createProductOptionRestResponseBuilder(): ProductOptionRestResponseBuilderInterface
    {
        return new ProductOptionRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createProductOptionMapper(),
            $this->createProductOptionSorter(),
            $this->getCurrencyClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductOptionsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOptionsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface
     */
    public function getProductOptionStorageClient(): ProductOptionsRestApiToProductOptionStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOptionsRestApiDependencyProvider::CLIENT_PRODUCT_OPTION_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ProductOptionsRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOptionsRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToCurrencyClientInterface
     */
    public function getCurrencyClient(): ProductOptionsRestApiToCurrencyClientInterface
    {
        return $this->getProvidedDependency(ProductOptionsRestApiDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapperInterface
     */
    public function createProductOptionMapper(): ProductOptionMapperInterface
    {
        return new ProductOptionMapper();
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface
     */
    public function createProductOptionSorter(): ProductOptionSorterInterface
    {
        return new ProductOptionSorter();
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Processor\Expander\CartItemExpanderInterface
     */
    public function createCartItemExpander(): CartItemExpanderInterface
    {
        return new CartItemExpander($this->createProductOptionStorageReader());
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Processor\Translator\ProductOptionTranslatorInterface
     */
    public function createProductOptionTranslator(): ProductOptionTranslatorInterface
    {
        return new ProductOptionTranslator($this->getGlossaryStorageClient());
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Processor\Expander\ProductOptionByProductAbstractSkuExpanderInterface
     */
    public function createProductOptionByProductAbstractSkuExpander(): ProductOptionByProductAbstractSkuExpanderInterface
    {
        return new ProductOptionByProductAbstractSkuExpander($this->createProductOptionStorageReader());
    }

    /**
     * @return \Spryker\Glue\ProductOptionsRestApi\Processor\Expander\ProductOptionByProductConcreteSkuExpanderInterface
     */
    public function createProductOptionByProductConcreteSkuExpander(): ProductOptionByProductConcreteSkuExpanderInterface
    {
        return new ProductOptionByProductConcreteSkuExpander($this->createProductOptionStorageReader());
    }
}
