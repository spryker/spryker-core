<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\BarcodeGenerator;

use Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginNotFoundException;
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
     * @param null|string $generatorPluginClassName
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodeGeneratorPlugin(?string $generatorPluginClassName): BarcodeGeneratorPluginInterface
    {
        if ($this->barcodePlugins && !$generatorPluginClassName) {
            return reset($this->barcodePlugins);
        }

        return $this->getPluginByClassNameCashed($generatorPluginClassName);
    }

    /**
     * @param string $fullClassName
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected function getPluginByClassNameCashed(string $fullClassName): BarcodeGeneratorPluginInterface
    {
        static $cashedPluginsMap;

        if (!array_key_exists($fullClassName, $cashedPluginsMap)) {
            $cashedPluginsMap[$fullClassName] = $this->getPluginByClassName($fullClassName);
        }

        return $cashedPluginsMap[$fullClassName];
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
        foreach ($this->barcodePlugins as $barcodePlugin) {
            if (get_class($barcodePlugin) === $fullClassName) {
                return $barcodePlugin;
            }
        }

        throw new BarcodeGeneratorPluginNotFoundException();
    }
}
