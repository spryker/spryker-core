<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode;

use Spryker\Service\Barcode\Model\BarcodeGenerator\BarcodeGenerator;
use Spryker\Service\Barcode\Model\BarcodeGenerator\BarcodeGeneratorInterface;
use Spryker\Service\Barcode\Model\BarcodeGeneratorPluginResolver\BarcodeGeneratorPluginResolver;
use Spryker\Service\Kernel\AbstractServiceFactory;

class BarcodeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Barcode\Model\BarcodeGenerator\BarcodeGeneratorInterface
     */
    public function createBarcodeGenerator(): BarcodeGeneratorInterface
    {
        return new BarcodeGenerator(
            $this->createBarcodePluginResolver()
        );
    }

    /**
     * @return \Spryker\Service\Barcode\Model\BarcodeGeneratorPluginResolver\BarcodeGeneratorPluginResolverInterface
     */
    public function createBarcodePluginResolver(): BarcodeGeneratorPluginResolver
    {
        return new BarcodeGeneratorPluginResolver($this->getBarcodePlugins());
    }

    /**
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[]
     */
    public function getBarcodePlugins(): array
    {
        return $this->getProvidedDependency(BarcodeDependencyProvider::BARCODE_PLUGINS);
    }
}
