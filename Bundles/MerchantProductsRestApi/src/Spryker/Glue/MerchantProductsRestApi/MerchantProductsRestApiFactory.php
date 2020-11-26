<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToLocaleClientInterface;
use Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\MerchantProductsRestApi\Processor\Expander\MerchantProductCartItemExpander;
use Spryker\Glue\MerchantProductsRestApi\Processor\Expander\MerchantProductCartItemExpanderInterface;

class MerchantProductsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantProductsRestApi\Processor\Expander\MerchantProductCartItemExpanderInterface
     */
    public function createMerchantProductCartItemExpander(): MerchantProductCartItemExpanderInterface
    {
        return new MerchantProductCartItemExpander(
            $this->getLocaleClient(),
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToLocaleClientInterface
     */
    public function getLocaleClient(): MerchantProductsRestApiToLocaleClientInterface
    {
        return $this->getProvidedDependency(MerchantProductsRestApiDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): MerchantProductsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
