<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductVolume\Business\PriceProductReader\PriceProductReader;
use Spryker\Zed\PriceProductVolume\Business\PriceProductReader\PriceProductReaderInterface;
use Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractor;
use Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface;
use Spryker\Zed\PriceProductVolume\Dependency\Facade\PriceProductVolumeToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductVolume\PriceProductVolumeDependencyProvider;

class PriceProductVolumeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor\VolumePriceExtractorInterface
     */
    public function createVolumePriceExtractor(): VolumePriceExtractorInterface
    {
        return new VolumePriceExtractor(
            $this->getUtilEncodingService(),
            $this->createPriceProductReader()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductVolume\Business\PriceProductReader\PriceProductReaderInterface
     */
    public function createPriceProductReader(): PriceProductReaderInterface
    {
        return new PriceProductReader(
            $this->getPriceProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductVolumeToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\PriceProductVolume\Dependency\Facade\PriceProductVolumeToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductVolumeToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
