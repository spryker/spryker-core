<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\BarcodeGenerator;

use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

interface BarcodeGeneratorPluginResolverInterface
{
    /**
     * @param null|string $generatorPlugin
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodeGeneratorPlugin(?string $generatorPlugin): BarcodeGeneratorPluginInterface;
}
