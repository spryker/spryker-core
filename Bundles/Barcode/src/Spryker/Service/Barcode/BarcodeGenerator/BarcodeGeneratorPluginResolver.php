<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\BarcodeGenerator;

use Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginNotFoundException;
use Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginsNotProvided;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class BarcodeGeneratorPluginResolver implements BarcodeGeneratorPluginResolverInterface
{
    /**
     * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[]
     */
    protected $barcodeGeneratorPlugins = [];

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[] $barcodeGeneratorPlugins
     */
    public function __construct(array $barcodeGeneratorPlugins)
    {
        $this->assertBarcodeGeneratorPlugins($barcodeGeneratorPlugins);

        $this->setBarcodeGeneratorPluginCache($barcodeGeneratorPlugins);
    }

    /**
     * @param null|string $generatorPluginClassName
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodeGeneratorPlugin(?string $generatorPluginClassName): BarcodeGeneratorPluginInterface
    {
        if (!$generatorPluginClassName) {
            return reset($this->barcodeGeneratorPlugins);
        }

        return $this->getPluginByClassName($generatorPluginClassName);
    }

    /**
     * @param array $barcodeGeneratorPlugins
     *
     * @throws \Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginsNotProvided
     *
     * @return void
     */
    protected function assertBarcodeGeneratorPlugins(array $barcodeGeneratorPlugins): void
    {
        if (!$barcodeGeneratorPlugins) {
            throw new BarcodeGeneratorPluginsNotProvided(
                'BarcodeGeneratorPluginResolver cannot work without plugin list'
            );
        }
    }

    /**
     * @param array $barcodeGeneratorPlugins
     *
     * @return void
     */
    protected function setBarcodeGeneratorPluginCache(array $barcodeGeneratorPlugins): void
    {
        foreach ($barcodeGeneratorPlugins as $barcodeGeneratorPlugin) {
            $this->barcodeGeneratorPlugins[get_class($barcodeGeneratorPlugin)] = $barcodeGeneratorPlugin;
        }
    }

    /**
     * @param string $fullClassName
     *
     * @throws \Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginNotFoundException
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected function getPluginByClassName(string $fullClassName): BarcodeGeneratorPluginInterface
    {
        if (!array_key_exists($fullClassName, $this->barcodeGeneratorPlugins)) {
            throw new BarcodeGeneratorPluginNotFoundException(
                sprintf(
                    'There is no plugin for barcode generation with class "%s".'
                    . ' Or it is not provided in BarcodeDependencyProvider::getBarcodeGeneratorPlugins()',
                    $fullClassName
                )
            );
        }

        return $this->barcodeGeneratorPlugins[$fullClassName];
    }
}
