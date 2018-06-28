<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode;

use Spryker\Service\Barcode\BarcodeGenerator\BarcodeGenerator;
use Spryker\Service\Barcode\BarcodeGenerator\BarcodeGeneratorInterface;
use Spryker\Service\Barcode\BarcodeGenerator\BarcodeGeneratorPluginResolver;
use Spryker\Service\Barcode\BarcodeGenerator\BarcodeGeneratorPluginResolverInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class BarcodeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Barcode\BarcodeGenerator\BarcodeGeneratorInterface
     */
    public function createBarcodeGenerator(): BarcodeGeneratorInterface
    {
        return new BarcodeGenerator(
            $this->createBarcodePluginResolver()
        );
    }

    /**
     * @return \Spryker\Service\Barcode\BarcodeGenerator\BarcodeGeneratorPluginResolverInterface
     */
    public function createBarcodePluginResolver(): BarcodeGeneratorPluginResolverInterface
    {
        return new BarcodeGeneratorPluginResolver($this->getBarcodeGeneratorPlugins());
    }

    /**
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[]
     */
    public function getBarcodeGeneratorPlugins(): array
    {
        return $this->getProvidedDependency(BarcodeDependencyProvider::PLUGINS_BARCODE_GENERATOR);
    }
}
