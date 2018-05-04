<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\BarcodeGeneratorPluginResolver;

use Spryker\Service\Barcode\Exception\PluginNotFoundException;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class BarcodeGeneratorPluginResolver implements BarcodeGeneratorPluginResolverInterface
{
    /**
     * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[]
     */
    protected $barcodePlugins;

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[] $barcodePlugins
     */
    public function __construct(array $barcodePlugins)
    {
        $this->barcodePlugins = $barcodePlugins;
    }

    /**
     * @param null|string $generatorPlugin
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodeGeneratorPlugin(?string $generatorPlugin): BarcodeGeneratorPluginInterface
    {
        if ($generatorPlugin === null) {
            return reset($this->barcodePlugins);
        }

        return $this->findByClassName($generatorPlugin);
    }

    /**
     * @param string $fullClassName
     *
     * @throws \Spryker\Service\Barcode\Exception\PluginNotFoundException
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected function findByClassName(string $fullClassName): BarcodeGeneratorPluginInterface
    {
        foreach ($this->barcodePlugins as $barcodePlugin) {
            if (get_class($barcodePlugin) === $fullClassName) {
                return $barcodePlugin;
            }
        }

        throw new PluginNotFoundException();
    }
}
