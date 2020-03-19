<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Builder\MerchantsRestResponseBuilder;
use Spryker\Glue\MerchantsRestApi\Processor\Builder\MerchantsRestResponseBuilderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantResourceMapper;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantResourceMapperInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantsReader;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantsReaderInterface;

/**
 * @method \Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig getConfig()
 */
class MerchantsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantsReaderInterface
     */
    public function createMerchantsReader(): MerchantsReaderInterface
    {
        return new MerchantsReader(
            $this->getMerchantStorageClient(),
            $this->createMerchantsRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Builder\MerchantsRestResponseBuilderInterface
     */
    public function createMerchantsRestResponseBuilder(): MerchantsRestResponseBuilderInterface
    {
        return new MerchantsRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createMerchantResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantsRestApiToMerchantsStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::CLIENT_MERCHANTS_STORAGE);
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantResourceMapperInterface
     */
    public function createMerchantResourceMapper(): MerchantResourceMapperInterface
    {
        return new MerchantResourceMapper();
    }
}
