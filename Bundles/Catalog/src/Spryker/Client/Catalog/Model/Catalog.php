<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model;

use Spryker\Client\Catalog\Model\Exception\ProductNotFoundException;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

/**
 * @deprecated This class will be removed because it's using a bad design to read search product data from Storage.
 */
class Catalog implements CatalogInterface
{

    const INDEXKEY_SKU = 'sku';
    const INDEXKEY_ID = 'id';
    const INDEXKEY_VARIETY = 'variety';

    const INDEXKEY_PRODUCT_BUNDLE_SKUS = 'bundleInfoSku';
    const INDEXKEY_PRODUCT_CONFIG_SKUS = 'configInfoSku';

    const PRODUCT_VARIETY_SIMPLE = 'Simple';
    const PRODUCT_VARIETY_CONFIG = 'Config';
    const PRODUCT_VARIETY_BUNDLE = 'Bundle';

    /**
     * @var \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected $productKeyBuilder;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageReader;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface $productKeyBuilder
     * @param \Spryker\Client\Storage\StorageClientInterface $storageReader
     * @param string $locale
     */
    public function __construct(
        KeyBuilderInterface $productKeyBuilder,
        StorageClientInterface $storageReader,
        $locale
    ) {
        $this->productKeyBuilder = $productKeyBuilder;
        $this->storageReader = $storageReader;
        $this->locale = $locale;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Client\Catalog\Model\Exception\ProductNotFoundException
     *
     * @return array
     */
    public function getProductDataById($id)
    {
        $productKey = $this->productKeyBuilder->generateKey($id, $this->locale);
        $productFromStorage = $this->storageReader->get($productKey);

        if (empty($productFromStorage)) {
            throw new ProductNotFoundException($id);
        }

        return $productFromStorage;
    }

    /**
     * @param array $ids
     * @param string|null $indexByKey
     *
     * @return array
     */
    public function getProductDataByIds(array $ids, $indexByKey = null)
    {
        $idKeys = [];
        foreach ($ids as $id) {
            $idKeys[] = $this->productKeyBuilder->generateKey($id, $this->locale);
        }
        $productsFromStorage = $this->storageReader->getMulti($idKeys);
        if ($productsFromStorage === null) {
            return [];
        }
        foreach ($productsFromStorage as $key => $product) {
            $productsFromStorage[$key] = $this->mergeAttributes(json_decode($product, true));
        }

        if ($indexByKey) {
            return $this->mapKeysToValue($indexByKey, $productsFromStorage);
        }

        return $productsFromStorage;
    }

    /**
     * @param string $key
     * @param array $productsFromStorage
     *
     * @return array
     */
    protected function mapKeysToValue($key, array $productsFromStorage)
    {
        $productsIndexedById = [];
        foreach ($productsFromStorage as $product) {
            $productsIndexedById[$product[$key]] = $product;
        }

        return $productsIndexedById;
    }

    /**
     * @param array $product
     *
     * @return array[]
     */
    public function getSubProducts(array $product)
    {
        $subProducts = [];
        switch ($product[self::INDEXKEY_VARIETY]) {
            case self::PRODUCT_VARIETY_CONFIG:
                return $this->getSubProductsBySkuIndex($product, self::INDEXKEY_PRODUCT_CONFIG_SKUS);
            case self::PRODUCT_VARIETY_BUNDLE:
                return $this->getSubProductsBySkuIndex($product, self::INDEXKEY_PRODUCT_BUNDLE_SKUS);
        }

        return $subProducts;
    }

    /**
     * @param array $product
     *
     * @return array
     */
    protected function mergeAttributes(array $product)
    {
        if (isset($product['attributes'])) {
            $productAttributes = $product['attributes'];

            return array_merge($product, $productAttributes);
        }

        return $product;
    }

}
