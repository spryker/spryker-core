<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Barcode\Communication;

use Spryker\Service\Barcode\BarcodeServiceInterface;
use Spryker\Zed\Barcode\BarcodeDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Barcode\BarcodeConfig getConfig()
 */
class BarcodeCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Service\Barcode\BarcodeServiceInterface
     */
    public function getBarcodeService(): BarcodeServiceInterface
    {
        return $this->getProvidedDependency(BarcodeDependencyProvider::SERVICE_BARCODE);
    }
}
