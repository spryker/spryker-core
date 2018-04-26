<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\BarcodeGeneratorToServiceFactoryBridge;

use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

interface BarcodeGeneratorToServiceFactoryBridgeInterface
{
    /**
     * @param string $fqcn
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function createPluginInstance(string $fqcn): BarcodeGeneratorPluginInterface;
}
