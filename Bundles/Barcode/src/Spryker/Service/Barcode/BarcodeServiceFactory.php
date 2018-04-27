<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode;

use Spryker\Service\Barcode\Model\BarcodeGenerator\BarcodeGenerator;
use Spryker\Service\Barcode\Model\BarcodeGenerator\BarcodeGeneratorInterface;
use Spryker\Service\Barcode\Model\BarcodeGeneratorToServiceFactoryBridge\BarcodeGeneratorToServiceFactoryBridge;
use Spryker\Service\Barcode\Model\BarcodeGeneratorToServiceFactoryBridge\BarcodeGeneratorToServiceFactoryBridgeInterface;
use Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class BarcodeServiceFactory extends AbstractServiceFactory
{
    /**
     * @param string $fullClassName
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function createPluginInstance(string $fullClassName): BarcodeGeneratorPluginInterface
    {
        return new $fullClassName();
    }

    /**
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function createBarcodeGenerator(): BarcodeGeneratorInterface
    {
        return new BarcodeGenerator(
            $this->getBarcodePlugins(),
            $this->createBarcodeGeneratorToServiceFactoryBridge()
        );
    }

    /**
     * @return \Spryker\Service\Barcode\Model\BarcodeGeneratorToServiceFactoryBridge\BarcodeGeneratorToServiceFactoryBridgeInterface
     */
    protected function createBarcodeGeneratorToServiceFactoryBridge(): BarcodeGeneratorToServiceFactoryBridgeInterface
    {
        return new BarcodeGeneratorToServiceFactoryBridge($this);
    }

    /**
     * @return \Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface
     */
    protected function getBarcodePlugins(): PluginCollectionInterface
    {
        return $this->getProvidedDependency(BarcodeDependencyProvider::BARCODE_PLUGINS);
    }
}
