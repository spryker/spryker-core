<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOffersRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductOffersRestApi\Dependency\Client\ProductOffersRestApiToProductOfferStorageClientInterface;
use Spryker\Glue\ProductOffersRestApi\Processor\Expander\ProductOfferExpander;
use Spryker\Glue\ProductOffersRestApi\Processor\Expander\ProductOfferExpanderInterface;
use Spryker\Glue\ProductOffersRestApi\Processor\Reader\ProductOfferReader;
use Spryker\Glue\ProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface;
use Spryker\Glue\ProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilder;
use Spryker\Glue\ProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface;

class ProductOffersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductOffersRestApi\Processor\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander(
            $this->createProductOfferReader(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->createProductOfferRestResponseBuilder(),
            $this->getProductOfferStorageClient(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductOffersRestApi\Dependency\Client\ProductOffersRestApiToProductOfferStorageClientInterface
     */
    public function getProductOfferStorageClient(): ProductOffersRestApiToProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOffersRestApiDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface
     */
    public function createProductOfferRestResponseBuilder(): ProductOfferRestResponseBuilderInterface
    {
        return new ProductOfferRestResponseBuilder(
            $this->getResourceBuilder(),
        );
    }
}
