<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\VolumePriceProduct;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\VolumePriceProduct\Business\VolumePriceExtractor\VolumePriceExtractor;
use Spryker\Client\VolumePriceProduct\Business\VolumePriceExtractor\VolumePriceExtractorInterface;
use Spryker\Client\VolumePriceProduct\Dependency\Service\VolumePriceProductToUtilEncodingServiceInterface;

class VolumePriceProductFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\VolumePriceProduct\Business\VolumePriceExtractor\VolumePriceExtractorInterface
     */
    public function createVolumePriceExtractor(): VolumePriceExtractorInterface
    {
        return new VolumePriceExtractor(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\VolumePriceProduct\Dependency\Service\VolumePriceProductToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): VolumePriceProductToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(VolumePriceProductDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
