<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\BarcodeGeneratorToServiceFactoryBridge;

use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class BarcodeGeneratorToServiceFactoryBridge implements BarcodeGeneratorToServiceFactoryBridgeInterface
{
    /**
     * @var \Spryker\Service\Barcode\BarcodeServiceFactory
     */
    protected $barcodeServiceFactory;

    /**
     * @param \Spryker\Service\Barcode\BarcodeServiceFactory $serviceFactory
     */
    public function __construct($serviceFactory)
    {
        $this->barcodeServiceFactory = $serviceFactory;
    }

    /**
     * @param string $fqcn
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function createPluginInstance(string $fqcn): BarcodeGeneratorPluginInterface
    {
        return $this->barcodeServiceFactory->createPluginInstance($fqcn);
    }
}
