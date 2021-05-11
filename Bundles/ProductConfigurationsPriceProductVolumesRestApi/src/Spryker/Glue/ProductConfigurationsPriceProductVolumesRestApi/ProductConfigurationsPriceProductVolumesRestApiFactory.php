<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Dependency\Service\ProductConfigurationsPriceProductVolumesRestApiToProductConfigurationServiceInterface;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Dependency\Service\ProductConfigurationsPriceProductVolumesRestApiToUtilEncodingServiceInterface;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Processor\Mapper\ProductConfigurationPriceProductVolumeMapper;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Processor\Mapper\ProductConfigurationPriceProductVolumeMapperInterface;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Processor\Mapper\RestCartItemProductConfigurationPriceProductVolumeMapper;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Processor\Mapper\RestCartItemProductConfigurationPriceProductVolumeMapperInterface;

class ProductConfigurationsPriceProductVolumesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Processor\Mapper\RestCartItemProductConfigurationPriceProductVolumeMapperInterface
     */
    public function createRestCartItemProductConfigurationPriceProductVolumeMapper(): RestCartItemProductConfigurationPriceProductVolumeMapperInterface
    {
        return new RestCartItemProductConfigurationPriceProductVolumeMapper(
            $this->getProductConfigurationService(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Processor\Mapper\ProductConfigurationPriceProductVolumeMapperInterface
     */
    public function createProductConfigurationPriceProductVolumeMapper(): ProductConfigurationPriceProductVolumeMapperInterface
    {
        return new ProductConfigurationPriceProductVolumeMapper();
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Dependency\Service\ProductConfigurationsPriceProductVolumesRestApiToProductConfigurationServiceInterface
     */
    public function getProductConfigurationService(): ProductConfigurationsPriceProductVolumesRestApiToProductConfigurationServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationsPriceProductVolumesRestApiDependencyProvider::SERVICE_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Dependency\Service\ProductConfigurationsPriceProductVolumesRestApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductConfigurationsPriceProductVolumesRestApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationsPriceProductVolumesRestApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
