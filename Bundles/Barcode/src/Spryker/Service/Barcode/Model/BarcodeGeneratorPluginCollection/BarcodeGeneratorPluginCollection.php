<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\BarcodeGeneratorPluginCollection;

use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class BarcodeGeneratorPluginCollection implements BarcodeGeneratorPluginCollectionInterface
{
    /**
     * @var array
     */
    protected $collection;

    /**
     * @param array $collection
     */
    public function __construct(array $collection = [])
    {
        foreach ($collection as $item) {
            $this->add($item);
        }
    }

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return BarcodeGeneratorPluginCollectionInterface
     */
    public function add(BarcodeGeneratorPluginInterface $generatorPlugin): BarcodeGeneratorPluginCollectionInterface
    {
        $this->collection[] = $generatorPlugin;
    }

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return int
     */
    public function find(BarcodeGeneratorPluginInterface $generatorPlugin): int
    {
        return array_search($generatorPlugin, $this->collection, true);
    }

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return BarcodeGeneratorPluginCollectionInterface
     */
    public function remove(BarcodeGeneratorPluginInterface $generatorPlugin): BarcodeGeneratorPluginCollectionInterface
    {
        $index = $this->find($generatorPlugin);

        if ($index !== false) {
            $this->collection = array_splice($this->collection, $index, 1);
        }

        return $this;
    }

    /**
     * @param int $index
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function get(int $index): BarcodeGeneratorPluginInterface
    {
        return $this->collection[$index];
    }

    /**
     * @return BarcodeGeneratorPluginCollectionInterface
     */
    public function clear(): BarcodeGeneratorPluginCollectionInterface
    {
        $this->collection = [];
        return $this;
    }
}
