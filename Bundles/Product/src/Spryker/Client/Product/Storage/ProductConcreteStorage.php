<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product\Storage;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Client\Product\Dependency\Client\ProductToStorageInterface;
use Spryker\Client\Product\Dependency\Service\ProductToUtilEncodingInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class ProductConcreteStorage implements ProductConcreteStorageInterface
{
    /**
     * @var \Spryker\Client\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\Product\Dependency\Client\ProductToStorageInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \Spryker\Client\Product\Dependency\Client\ProductToStorageInterface $storage
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param \Spryker\Client\Product\Dependency\Service\ProductToUtilEncodingInterface $utilEncodingService
     * @param string $localeName
     */
    public function __construct(
        ProductToStorageInterface $storage,
        KeyBuilderInterface $keyBuilder,
        ProductToUtilEncodingInterface $utilEncodingService,
        $localeName
    ) {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->utilEncodingService = $utilEncodingService;
        $this->localeName = $localeName;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return mixed
     */
    public function getProductConcreteById($idProductConcrete)
    {
        $key = $this->keyBuilder->generateKey($idProductConcrete, $this->localeName);
        $product = $this->storage->get($key);

        return $product;
    }

    /**
     * @param array $idProductConcreteCollection
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    public function getProductConcreteCollection(array $idProductConcreteCollection)
    {
        $jsonData = $this->getProductConcreteStorageData($idProductConcreteCollection);

        $result = [];
        foreach ($jsonData as $key => $json) {
            $data = (array)$this->utilEncodingService->decodeJson($json, true);
            $result[] = $this->mapStorageProduct($data);
        }

        return $result;
    }

    /**
     * @param array $idProductConcreteCollection
     *
     * @return array
     */
    protected function getProductConcreteStorageData(array $idProductConcreteCollection)
    {
        $storageKeyCollection = $this->getStorageKeyCollection($idProductConcreteCollection);

        return $this->storage->getMulti($storageKeyCollection);
    }

    /**
     * @param array $idProductConcreteCollection
     *
     * @return array
     */
    protected function getStorageKeyCollection(array $idProductConcreteCollection)
    {
        $keyCollection = [];
        foreach ($idProductConcreteCollection as $idProductConcrete) {
            $key = $this->keyBuilder->generateKey($idProductConcrete, $this->localeName);
            $keyCollection[] = $key;
        }

        return $keyCollection;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function mapStorageProduct(array $data)
    {
        $storageProduct = new StorageProductTransfer();
        $storageProduct->fromArray($data, true);

        return $storageProduct;
    }
}
