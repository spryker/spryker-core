<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToMerchantProductOfferStorageClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductStorageClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\Expander\ProductOfferPriceExpander;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\Expander\ProductOfferPriceExpanderInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\Mapper\ProductOfferPriceMapper;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\Mapper\ProductOfferPriceMapperInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\Reader\ProductOfferPriceReader;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\Reader\ProductOfferPriceReaderInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\RestResponseBuilder\ProductOfferPriceRestResponseBuilder;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\RestResponseBuilder\ProductOfferPriceRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\ProductOfferPricesRestApi\ProductOfferPricesRestApiConfig getConfig()
 */
class ProductOfferPricesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductOfferPricesRestApi\Processor\Reader\ProductOfferPriceReaderInterface
     */
    public function createProductOfferPriceReader(): ProductOfferPriceReaderInterface
    {
        return new ProductOfferPriceReader(
            $this->getMerchantProductOfferStorageClient(),
            $this->getProductStorageClient(),
            $this->getPriceProductStorageClient(),
            $this->getPriceProductClient(),
            $this->createProductOfferPriceRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductOfferPricesRestApi\Processor\RestResponseBuilder\ProductOfferPriceRestResponseBuilderInterface
     */
    public function createProductOfferPriceRestResponseBuilder(): ProductOfferPriceRestResponseBuilderInterface
    {
        return new ProductOfferPriceRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createProductOfferPriceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductOfferPricesRestApi\Processor\Mapper\ProductOfferPriceMapperInterface
     */
    public function createProductOfferPriceMapper(): ProductOfferPriceMapperInterface
    {
        return new ProductOfferPriceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductOfferPricesRestApi\Processor\Expander\ProductOfferPriceExpanderInterface
     */
    public function createProductOfferPriceExpander(): ProductOfferPriceExpanderInterface
    {
        return new ProductOfferPriceExpander($this->createProductOfferPriceReader());
    }

    /**
     * @return \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): ProductOfferPricesRestApiToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferPricesRestApiDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToMerchantProductOfferStorageClientInterface
     */
    public function getMerchantProductOfferStorageClient(): ProductOfferPricesRestApiToMerchantProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferPricesRestApiDependencyProvider::CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductOfferPricesRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferPricesRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductClientInterface
     */
    public function getPriceProductClient(): ProductOfferPricesRestApiToPriceProductClientInterface
    {
        return $this->getProvidedDependency(ProductOfferPricesRestApiDependencyProvider::CLIENT_PRICE_PRODUCT);
    }
}
