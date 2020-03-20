<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper\MerchantOpeningHourMapper;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper\MerchantOpeningHourMapperInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Reader\MerchantOpeningHourReader;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Reader\MerchantOpeningHourReaderInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHourRestResponseBuilder;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHourRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\MerchantOpeningHoursRestApi\MerchantOpeningHoursRestApiConfig getConfig()
 */
class MerchantOpeningHoursRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Reader\MerchantOpeningHourReaderInterface
     */
    public function createMerchantOpeningHoursReader(): MerchantOpeningHourReaderInterface
    {
        return new MerchantOpeningHourReader(
            $this->getMerchantOpeningHoursStorageClient(),
            $this->getMerchantStorageClient(),
            $this->createMerchantOpeningHoursRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHourRestResponseBuilderInterface
     */
    public function createMerchantOpeningHoursRestResponseBuilder(): MerchantOpeningHourRestResponseBuilderInterface
    {
        return new MerchantOpeningHourRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createMerchantResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper\MerchantOpeningHourMapperInterface
     */
    public function createMerchantResourceMapper(): MerchantOpeningHourMapperInterface
    {
        return new MerchantOpeningHourMapper();
    }

    /**
     * @return \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface
     */
    public function getMerchantOpeningHoursStorageClient(): MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantOpeningHoursRestApiDependencyProvider::CLIENT_MERCHANT_OPENING_HOURS_STORAGE);
    }

    /**
     * @return \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantOpeningHoursRestApiToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantOpeningHoursRestApiDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }
}
