<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\CartItem\Expander\CartItemExpander;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\CartItem\Expander\CartItemExpanderInterface;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\CartItem\Mapper\CartItemsAttributesMapper;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\CartItem\Mapper\CartItemsAttributesMapperInterface;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Expander\ProductOfferExpander;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Expander\ProductOfferExpanderInterface;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader\ProductOfferReader;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilder;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface;

class MerchantProductOffersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->createProductOfferRestResponseBuilder(),
            $this->getMerchantProductOfferStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantProductOffersRestApi\Processor\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander($this->createProductOfferReader());
    }

    /**
     * @return \Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface
     */
    public function createProductOfferRestResponseBuilder(): ProductOfferRestResponseBuilderInterface
    {
        return new ProductOfferRestResponseBuilder(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantProductOffersRestApi\Processor\CartItem\Expander\CartItemExpanderInterface
     */
    public function createCartItemExpander(): CartItemExpanderInterface
    {
        return new CartItemExpander($this->getMerchantProductOfferStorageClient());
    }

    /**
     * @return \Spryker\Glue\MerchantProductOffersRestApi\Processor\CartItem\Mapper\CartItemsAttributesMapperInterface
     */
    public function createCartItemsAttributesMapper(): CartItemsAttributesMapperInterface
    {
        return new CartItemsAttributesMapper();
    }

    /**
     * @return \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface
     */
    public function getMerchantProductOfferStorageClient(): MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOffersRestApiDependencyProvider::CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE);
    }
}
