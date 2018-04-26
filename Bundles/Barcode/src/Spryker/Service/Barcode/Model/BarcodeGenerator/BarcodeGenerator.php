<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\BarcodeGenerator;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Barcode\Exception\PluginNotFoundException;
use Spryker\Service\Barcode\Model\BarcodeGeneratorToServiceFactoryBridge\BarcodeGeneratorToServiceFactoryBridgeInterface;
use Spryker\Service\Barcode\Model\PluginAvailabilityChecker\PluginAvailabilityCheckerInterface;
use Spryker\Service\Barcode\Model\PluginClassNameResolver\PluginClassNameResolverInterface;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class BarcodeGenerator implements BarcodeGeneratorInterface
{
    /**
     * @var \Spryker\Service\Barcode\Model\PluginAvailabilityChecker\PluginAvailabilityCheckerInterface
     */
    protected $pluginAvailabilityChecker;

    /**
     * @var \Spryker\Service\Barcode\Model\PluginClassNameResolver\PluginClassNameResolverInterface
     */
    protected $pluginClassNameResolver;

    /**
     * @var \Spryker\Service\Barcode\Model\BarcodeGeneratorToServiceFactoryBridge\BarcodeGeneratorToServiceFactoryBridgeInterface
     */
    protected $serviceFactoryBridge;

    /**
     * @param \Spryker\Service\Barcode\Model\PluginAvailabilityChecker\PluginAvailabilityCheckerInterface $availabilityChecker
     * @param \Spryker\Service\Barcode\Model\PluginClassNameResolver\PluginClassNameResolverInterface $classNameResolver
     * @param \Spryker\Service\Barcode\Model\BarcodeGeneratorToServiceFactoryBridge\BarcodeGeneratorToServiceFactoryBridgeInterface $serviceFactoryBridge
     */
    public function __construct(PluginAvailabilityCheckerInterface $availabilityChecker, PluginClassNameResolverInterface $classNameResolver, BarcodeGeneratorToServiceFactoryBridgeInterface $serviceFactoryBridge)
    {
        $this->pluginAvailabilityChecker = $availabilityChecker;
        $this->pluginClassNameResolver = $classNameResolver;
        $this->serviceFactoryBridge = $serviceFactoryBridge;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $text
     * @param string|null $generatorPlugin
     *
     * @throws \Spryker\Service\Barcode\Exception\PluginNotFoundException
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(string $text, string $generatorPlugin = null): BarcodeResponseTransfer
    {
        $fqcn = $this->pluginClassNameResolver->resolveBarcodeGeneratorPluginClassName($generatorPlugin);

        if ($this->pluginAvailabilityChecker->check($fqcn)) {
            return $this->createPluginInstance($fqcn)->generate($text);
        }

        throw new PluginNotFoundException();
    }

    /**
     * @param string $resolvedClassName
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected function createPluginInstance(string $resolvedClassName): BarcodeGeneratorPluginInterface
    {
        return $this->serviceFactoryBridge->createPluginInstance($resolvedClassName);
    }
}
