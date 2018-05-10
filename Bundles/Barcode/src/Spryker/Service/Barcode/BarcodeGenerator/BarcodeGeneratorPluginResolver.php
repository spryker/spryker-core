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
        if ($this->barcodeGeneratorPlugins && !$generatorPluginClassName) {
            return reset($this->barcodeGeneratorPlugins);
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
        /**
         * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[] $cashedPluginsMap
         */
        static $cashedPluginsMap = [];

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
        foreach ($this->barcodeGeneratorPlugins as $barcodePlugin) {
            if (get_class($barcodePlugin) === $fullClassName) {
                return $barcodePlugin;
            }
        }

        throw new BarcodeGeneratorPluginNotFoundException(
            sprintf(
                'There is no plugin for barcode generation with class "%s".'
                . ' Or it is not provided in BarcodeDependencyProvider::getBarcodeGeneratorPlugins()',
                $fullClassName
            )
        );
    }
}
