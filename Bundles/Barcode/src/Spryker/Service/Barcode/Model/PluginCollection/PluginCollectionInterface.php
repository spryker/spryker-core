<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\PluginCollection;

use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

interface PluginCollectionInterface
{
    /**
     * @param string $fullClassName
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function findByClassName(string $fullClassName): BarcodeGeneratorPluginInterface;

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return $this
     */
    public function add(BarcodeGeneratorPluginInterface $generatorPlugin): self;

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return $this
     */
    public function remove(BarcodeGeneratorPluginInterface $generatorPlugin): self;

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return int
     */
    public function find(BarcodeGeneratorPluginInterface $generatorPlugin): int;

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return bool
     */
    public function contains(BarcodeGeneratorPluginInterface $generatorPlugin): bool;

    /**
     * @param int $index
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function get(int $index): BarcodeGeneratorPluginInterface;

    /**
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function first(): BarcodeGeneratorPluginInterface;

    /**
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function last(): BarcodeGeneratorPluginInterface;

    /**
     * @return array
     */
    public function toArray(): array;
}
