<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode;

use Spryker\Service\Barcode\Model\BarcodeGenerator\BarcodeGenerator;
use Spryker\Service\Barcode\Model\BarcodeGenerator\BarcodeGeneratorInterface;
use Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class BarcodeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function createBarcodeGenerator(): BarcodeGeneratorInterface
    {
        return new BarcodeGenerator(
            $this->getBarcodePlugins()
        );
    }

    /**
     * @return \Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface
     */
    protected function getBarcodePlugins(): PluginCollectionInterface
    {
        return $this->getProvidedDependency(BarcodeDependencyProvider::BARCODE_PLUGINS);
    }
}
