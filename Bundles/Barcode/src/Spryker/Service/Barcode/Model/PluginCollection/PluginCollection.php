<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\PluginCollection;

use Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException;
use Spryker\Service\Barcode\Exception\PluginNotFoundException;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class PluginCollection implements PluginCollectionInterface
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
     * @param string $fullClassName
     *
     * @throws \Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException
     * @throws \Spryker\Service\Barcode\Exception\PluginNotFoundException
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function findByClassName(string $fullClassName): BarcodeGeneratorPluginInterface
    {
        if ($this->isEmpty()) {
            throw new PluginCollectionIsEmptyException();
        }

        $filtered = array_filter($this->collection, function ($item) use ($fullClassName) {
            return get_class($item) === $fullClassName;
        });

        if (count($filtered) === 0) {
            throw new PluginNotFoundException();
        }

        return reset($filtered);
    }

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @return \Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface
     */
    public function add(BarcodeGeneratorPluginInterface $generatorPlugin): PluginCollectionInterface
    {
        $this->collection[] = $generatorPlugin;
    }

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @throws \Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException
     *
     * @return \Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface
     */
    public function remove(BarcodeGeneratorPluginInterface $generatorPlugin): PluginCollectionInterface
    {
        if ($this->isEmpty()) {
            throw new PluginCollectionIsEmptyException();
        }

        $index = $this->find($generatorPlugin);

        if ($index !== false) {
            $this->collection = array_splice($this->collection, $index, 1);
        }

        return $this;
    }

    /**
     * @param int $index
     *
     * @throws \Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function get(int $index): BarcodeGeneratorPluginInterface
    {
        if ($this->isEmpty()) {
            throw new PluginCollectionIsEmptyException();
        }

        return $this->collection[$index];
    }

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @throws \Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException
     *
     * @return int
     */
    public function find(BarcodeGeneratorPluginInterface $generatorPlugin): int
    {
        if ($this->isEmpty()) {
            throw new PluginCollectionIsEmptyException();
        }

        $index = array_search($generatorPlugin, $this->collection, true);

        return ($index !== false)
            ? $index
            : -1;
    }

    /**
     * @param \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface $generatorPlugin
     *
     * @throws \Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException
     *
     * @return bool
     */
    public function contains(BarcodeGeneratorPluginInterface $generatorPlugin): bool
    {
        if ($this->isEmpty()) {
            throw new PluginCollectionIsEmptyException();
        }

        return $this->find($generatorPlugin) > -1;
    }

    /**
     * @throws \Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function first(): BarcodeGeneratorPluginInterface
    {
        if ($this->isEmpty()) {
            throw new PluginCollectionIsEmptyException();
        }

        return reset($this->collection);
    }

    /**
     * @throws \Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function last(): BarcodeGeneratorPluginInterface
    {
        if ($this->isEmpty()) {
            throw new PluginCollectionIsEmptyException();
        }

        return end($this->collection);
    }

    /**
     * @throws \Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException
     *
     * @return \Spryker\Service\Barcode\Model\PluginCollection\PluginCollectionInterface
     */
    public function clear(): PluginCollectionInterface
    {
        if ($this->isEmpty()) {
            throw new PluginCollectionIsEmptyException();
        }

        $this->collection = [];

        return $this;
    }

    /**
     * @throws \Spryker\Service\Barcode\Exception\PluginCollectionIsEmptyException
     *
     * @return array
     */
    public function toArray(): array
    {
        if ($this->isEmpty()) {
            throw new PluginCollectionIsEmptyException();
        }

        return $this->collection;
    }

    /**
     * @return bool
     */
    protected function isEmpty(): bool
    {
        return count($this->collection) === 0;
    }
}
