<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductVolume;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToLocaleClientInterface;
use Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToPriceProductStorageClientInterface;
use Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToProductStorageClientInterface;
use Spryker\Client\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface;
use Spryker\Client\PriceProductVolume\PriceExtractor\PriceProductReader\PriceProductReader;
use Spryker\Client\PriceProductVolume\PriceExtractor\PriceProductReader\PriceProductReaderInterface;
use Spryker\Client\PriceProductVolume\PriceExtractor\VolumePriceExtractor\VolumePriceExtractor;
use Spryker\Client\PriceProductVolume\PriceExtractor\VolumePriceExtractor\VolumePriceExtractorInterface;

class PriceProductVolumeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductVolume\PriceExtractor\VolumePriceExtractor\VolumePriceExtractorInterface
     */
    public function createVolumePriceExtractor(): VolumePriceExtractorInterface
    {
        return new VolumePriceExtractor(
            $this->getUtilEncodingService(),
            $this->createPriceProductReader()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductVolume\PriceExtractor\PriceProductReader\PriceProductReaderInterface
     */
    public function createPriceProductReader(): PriceProductReaderInterface
    {
        return new PriceProductReader(
            $this->getLocaleClient(),
            $this->getProductStorageClient(),
            $this->getPriceProductStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductVolumeToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToProductStorageClientInterface
     */
    public function getProductStorageClient(): PriceProductVolumeToProductStorageClientInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): PriceProductVolumeToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\PriceProductVolume\Dependency\Client\PriceProductVolumeToLocaleClientInterface
     */
    public function getLocaleClient(): PriceProductVolumeToLocaleClientInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::CLIENT_LOCALE);
    }
}
