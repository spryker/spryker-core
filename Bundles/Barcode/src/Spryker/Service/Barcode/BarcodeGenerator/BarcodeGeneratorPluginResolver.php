<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\BarcodeGenerator;

use Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginAlreadyRegisteredException;
use Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginNotFoundException;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class BarcodeGeneratorPluginResolver implements BarcodeGeneratorPluginResolverInterface
{
    /**
     * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[]
     */
    protected $barcodeGeneratorPlugins = [];

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[] $barcodeGeneratorPlugins
     *
     * @throws \Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginAlreadyRegisteredException
     */
    public function __construct(array $barcodeGeneratorPlugins)
    {
        foreach ($barcodeGeneratorPlugins as $barcodeGeneratorPlugin) {
            if (in_array($barcodeGeneratorPlugin, $this->barcodeGeneratorPlugins)) {
                throw new BarcodeGeneratorPluginAlreadyRegisteredException();
            }

            $this->barcodeGeneratorPlugins[] = $barcodeGeneratorPlugin;
        }

        $this->barcodeGeneratorPlugins = $barcodeGeneratorPlugins;
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

        return $this->findByClassName($generatorPluginClassName);
    }

    /**
     * @param string $fullClassName
     *
     * @throws \Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginNotFoundException
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected function findByClassName(string $fullClassName): BarcodeGeneratorPluginInterface
    {
        foreach ($this->barcodeGeneratorPlugins as $barcodePlugin) {
            if (get_class($barcodePlugin) === $fullClassName) {
                return $barcodePlugin;
            }
        }

        throw new BarcodeGeneratorPluginNotFoundException();
    }
}
