<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PriceProductVolumesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\PriceProductVolumesRestApi\Dependency\Client\PriceProductVolumesRestApiToPriceProductVolumeClientInterface;
use Spryker\Glue\PriceProductVolumesRestApi\Dependency\Service\PriceProductVolumesRestApiToUtilEncodingServiceInterface;
use Spryker\Glue\PriceProductVolumesRestApi\Processor\Mapper\PriceProductVolumeMapper;
use Spryker\Glue\PriceProductVolumesRestApi\Processor\Mapper\PriceProductVolumeMapperInterface;

class PriceProductVolumesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PriceProductVolumesRestApi\Processor\Mapper\PriceProductVolumeMapperInterface
     */
    public function createPriceProductVolumeMapper(): PriceProductVolumeMapperInterface
    {
        return new PriceProductVolumeMapper(
            $this->getPriceProductVolumeClient(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Glue\PriceProductVolumesRestApi\Dependency\Client\PriceProductVolumesRestApiToPriceProductVolumeClientInterface
     */
    public function getPriceProductVolumeClient(): PriceProductVolumesRestApiToPriceProductVolumeClientInterface
    {
        return $this->getProvidedDependency(PriceProductVolumesRestApiDependencyProvider::CLIENT_PRICE_PRODUCT_VOLUME);
    }

    /**
     * @return \Spryker\Glue\PriceProductVolumesRestApi\Dependency\Service\PriceProductVolumesRestApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductVolumesRestApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductVolumesRestApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
