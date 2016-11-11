<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product\Storage;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Client\Product\Dependency\Client\ProductToStorageInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\UtilEncoding\Json;

class ProductConcreteStorage implements ProductConcreteStorageInterface
{

    /**
     * @var \Spryker\Client\Product\Dependency\Client\ProductToStorageInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \Spryker\Client\Product\Dependency\Client\ProductToStorageInterface $storage
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $localeName
     */
    public function __construct(ProductToStorageInterface $storage, KeyBuilderInterface $keyBuilder, $localeName)
    {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
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
     * @return array|\Generated\Shared\Transfer\StorageProductTransfer[]
     */
    public function getProductConcreteCollection(array $idProductConcreteCollection)
    {
        $keyCollection = [];
        foreach ($idProductConcreteCollection as $idProductConcrete) {
            $key = $this->keyBuilder->generateKey($idProductConcrete, $this->localeName);
            $keyCollection[] = $key;
        }

        $jsonData = $this->storage->getMulti($keyCollection);
        $jsonUtil = new Json();

        $result = [];
        foreach ($jsonData as $key => $json) {
            $data = $jsonUtil->decode($json, true);
            $result[] = $this->mapStorageProduct($data);
        }

        return $result;
    }

    /**
     * @param mixed $data
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function mapStorageProduct($data)
    {
        $storageProduct = new StorageProductTransfer();
        if (is_array($data)) {
            $storageProduct->fromArray($data, true);
        }

        return $storageProduct;
    }


}
