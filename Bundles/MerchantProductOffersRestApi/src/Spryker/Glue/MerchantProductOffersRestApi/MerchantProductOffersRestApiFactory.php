<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Expander\CartItemExpander;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Expander\CartItemExpanderInterface;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Mapper\CartItemsAttributesMapper;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Mapper\CartItemsAttributesMapperInterface;

class MerchantProductOffersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantProductOffersRestApi\Processor\Expander\CartItemExpanderInterface
     */
    public function createCartItemExpander(): CartItemExpanderInterface
    {
        return new CartItemExpander($this->getMerchantProductOfferStorageClient());
    }

    /**
     * @return \Spryker\Glue\MerchantProductOffersRestApi\Processor\Mapper\CartItemsAttributesMapperInterface
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
