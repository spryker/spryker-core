<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\UrlResolver\MerchantUrlResolver;
use Spryker\Glue\MerchantsRestApi\Processor\UrlResolver\MerchantUrlResolverInterface;

class MerchantsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\UrlResolver\MerchantUrlResolverInterface
     */
    public function createMerchantUrlResolver(): MerchantUrlResolverInterface
    {
        return new MerchantUrlResolver($this->getMerchantStorageClient());
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantsRestApiToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }
}
