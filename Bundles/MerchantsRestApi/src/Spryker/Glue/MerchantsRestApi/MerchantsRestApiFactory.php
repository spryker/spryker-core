<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapper;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReader;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilder;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig getConfig()
 */
class MerchantsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface
     */
    public function createMerchantsReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getMerchantStorageClient(),
            $this->createMerchantsRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface
     */
    public function createMerchantsRestResponseBuilder(): MerchantRestResponseBuilderInterface
    {
        return new MerchantRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createMerchantResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantsRestApiToMerchantsStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface
     */
    public function createMerchantResourceMapper(): MerchantMapperInterface
    {
        return new MerchantMapper();
    }
}
