<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantResourceRelationshipExpander;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantResourceRelationshipExpanderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapper;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantStorageReader;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantStorageReaderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilder;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface;

class MerchantsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantResourceRelationshipExpanderInterface
     */
    public function createMerchantResourceRelationshipExpander(): MerchantResourceRelationshipExpanderInterface
    {
        return new MerchantResourceRelationshipExpander($this->createMerchantStorageReader());
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantsRestApiToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantStorageReaderInterface
     */
    public function createMerchantStorageReader(): MerchantStorageReaderInterface
    {
        return new MerchantStorageReader($this->getMerchantStorageClient(), $this->createMerchantRestResponseBuilder());
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface
     */
    public function createMerchantRestResponseBuilder(): MerchantRestResponseBuilderInterface
    {
        return new MerchantRestResponseBuilder($this->getResourceBuilder(), $this->createMerchantMapper());
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface
     */
    public function createMerchantMapper(): MerchantMapperInterface
    {
        return new MerchantMapper();
    }
}
