<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\BarcodeLaminas;

use Spryker\Service\BarcodeLaminas\BarcodeGenerator\BarcodeGenerator;
use Spryker\Service\BarcodeLaminas\BarcodeGenerator\BarcodeGeneratorInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class BarcodeLaminasServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\BarcodeLaminas\BarcodeGenerator\BarcodeGeneratorInterface
     */
    public function createBarcodeGenerator(): BarcodeGeneratorInterface
    {
        return new BarcodeGenerator();
    }
}
