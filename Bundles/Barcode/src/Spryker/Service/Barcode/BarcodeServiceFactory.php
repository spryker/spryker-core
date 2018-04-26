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
use Spryker\Service\Barcode\Model\PluginAvailabilityChecker\PluginAvailabilityChecker;
use Spryker\Service\Barcode\Model\PluginAvailabilityChecker\PluginAvailabilityCheckerInterface;
use Spryker\Service\Barcode\Model\PluginClassNameResolver\PluginClassNameResolver;
use Spryker\Service\Barcode\Model\PluginClassNameResolver\PluginClassNameResolverInterface;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class BarcodeServiceFactory extends AbstractServiceFactory
{
    /**
     * TODO: I don't really like this method; need to rework the logic somehow
     *
     * @param string $fqcn
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function createPluginInstance(string $fqcn): BarcodeGeneratorPluginInterface
    {
        return new $fqcn();
    }

    /**
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function createBarcodeGenerator(): BarcodeGeneratorInterface
    {
        return new BarcodeGenerator(
            $this->createPluginAvailabilityChecker(),
            $this->createPluginClassNameResolver(),
            $this->createBarcodeGeneratorToServiceFactoryBridge()
        );
    }

    /**
     * @return \Spryker\Service\Barcode\Model\PluginAvailabilityChecker\PluginAvailabilityCheckerInterface
     */
    protected function createPluginAvailabilityChecker(): PluginAvailabilityCheckerInterface
    {
        return new PluginAvailabilityChecker();
    }

    /**
     * @return \Spryker\Service\Barcode\Model\PluginClassNameResolver\PluginClassNameResolverInterface
     */
    protected function createPluginClassNameResolver(): PluginClassNameResolverInterface
    {
        return new PluginClassNameResolver();
    }

    /**
     * @return \Spryker\Service\Barcode\Model\BarcodeGeneratorToServiceFactoryBridge\BarcodeGeneratorToServiceFactoryBridgeInterface
     */
    protected function createBarcodeGeneratorToServiceFactoryBridge(): BarcodeGeneratorToServiceFactoryBridgeInterface
    {
        return new BarcodeGeneratorToServiceFactoryBridge($this);
    }
}
