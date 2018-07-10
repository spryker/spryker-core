<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\VolumePriceProduct\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\VolumePriceProduct\Business\VolumePriceExtractor\VolumePriceExtractor;
use Spryker\Zed\VolumePriceProduct\Business\VolumePriceExtractor\VolumePriceExtractorInterface;
use Spryker\Zed\VolumePriceProduct\Dependency\Service\VolumePriceProductToUtilEncodingServiceInterface;
use Spryker\Zed\VolumePriceProduct\VolumePriceProductDependencyProvider;

class VolumePriceProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\VolumePriceProduct\Business\VolumePriceExtractor\VolumePriceExtractorInterface
     */
    public function createVolumePriceExtractor(): VolumePriceExtractorInterface
    {
        return new VolumePriceExtractor(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\VolumePriceProduct\Dependency\Service\VolumePriceProductToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): VolumePriceProductToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(VolumePriceProductDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
