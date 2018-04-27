<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\BarcodeGenerator;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class BarcodeGenerator implements BarcodeGeneratorInterface
{
    /**
     * @var \Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface
     */
    protected $pluginCollection;

    /**
     * @param \Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface $pluginCollection
     */
    public function __construct(PluginCollectionInterface $pluginCollection)
    {
        $this->pluginCollection = $pluginCollection;
    }

    /**
     * @param string $text
     * @param null|string $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(string $text, ?string $generatorPlugin): BarcodeResponseTransfer
    {
        return $this->getBarcodeGeneratorPlugin($generatorPlugin)->generate($text);
    }

    /**
     * @param null|string $generatorPlugin
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected function getBarcodeGeneratorPlugin(?string $generatorPlugin): BarcodeGeneratorPluginInterface
    {
        if ($generatorPlugin === null) {
            return $this->pluginCollection->first();
        }

        return $this->pluginCollection->findByClassName($generatorPlugin);
    }
}
