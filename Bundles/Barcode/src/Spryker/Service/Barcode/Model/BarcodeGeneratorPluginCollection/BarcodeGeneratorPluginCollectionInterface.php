<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\BarcodeGeneratorPluginCollection;

use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

interface BarcodeGeneratorPluginCollectionInterface
{
    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return $this
     */
    public function add(BarcodeGeneratorPluginInterface $generatorPlugin): self;

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return int
     */
    public function find(BarcodeGeneratorPluginInterface $generatorPlugin): int;

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return $this
     */
    public function remove(BarcodeGeneratorPluginInterface $generatorPlugin): self;

    /**
     * @param int $index
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function get(int $index): BarcodeGeneratorPluginInterface;
}
