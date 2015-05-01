<?php
namespace SprykerFeature\Sdk\Catalog\Model;

use SprykerFeature\Shared\Catalog\Code\Storage\StorageKeyGenerator;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\KvStorage\Client\ReadInterface;
use SprykerFeature\Sdk\Catalog\Model\Exception\ProductNotFoundException;

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
     * @var KeyBuilderInterface
     */
    protected $productKeyBuilder;

    /**
     * @var ReadInterface
     */
    protected $storageReader;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param KeyBuilderInterface $productKeyBuilder
     * @param ReadInterface       $storageReader
     * @param string              $locale
     */
    public function __construct(
        KeyBuilderInterface $productKeyBuilder,
        ReadInterface $storageReader,
        $locale
    ) {
        $this->productKeyBuilder = $productKeyBuilder;
        $this->storageReader = $storageReader;
        $this->locale = $locale;
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception\ProductNotFoundException
     */
    public function getProductDataById($id)
    {
        $productKey = StorageKeyGenerator::getProductKey($id);
        $productFromStorage = $this->storageReader->get($productKey);

        if (empty($productFromStorage)) {
            throw new ProductNotFoundException($id);
        }

        return $productFromStorage;
    }

    /**
     * @param string $sku
     * @return array
     * @throws Exception\ProductNotFoundException
     */
    public function getProductDataBySku($sku)
    {
        $skuKey = StorageKeyGenerator::getProductSkuKey($sku);
        $productId = $this->storageReader->get($skuKey);

        return self::getProductDataById($productId);
    }

    /**
     * @param array       $ids
     * @param string|null $indexByKey
     * @return array[]
     * @throws Exception\ProductNotFoundException
     */
    public function getProductDataByIds(array $ids, $indexByKey = null)
    {
        $productKeys = [];
        foreach ($ids as $id) {
            $productKeys[] = StorageKeyGenerator::getProductKey($id);
        }
        $productsFromStorage = array_filter($this->storageReader->getMulti($productKeys));

        if (empty($productsFromStorage)) {
            throw new ProductNotFoundException('');
        }

        if ($indexByKey) {
            $productsFromStorage = $this->mapKeysToValue($indexByKey, $productsFromStorage);
        }

        return $productsFromStorage;
    }

    /**
     * @param array $skus
     * @param null  $indexByKey
     * @return array
     */
    public function getProductDataBySkus(array $skus, $indexByKey = null)
    {
        $skuKeys = [];
        foreach ($skus as $sku) {
            $skuKeys[] = $this->productKeyBuilder->generateKey($sku, $this->locale);
        }
        $productsFromStorage = $this->storageReader->getMulti($skuKeys);
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
     * @return array|\array[]
     */
    public function getSubProducts(array $product)
    {
        $subProducts = [];
        switch($product[self::INDEXKEY_VARIETY]) {
            case self::PRODUCT_VARIETY_CONFIG :
                return $this->getSubProductsBySkuIndex($product, self::INDEXKEY_PRODUCT_CONFIG_SKUS);
                break;
            case self::PRODUCT_VARIETY_BUNDLE :
                return $this->getSubProductsBySkuIndex($product, self::INDEXKEY_PRODUCT_BUNDLE_SKUS);
                break;
        }

        return $subProducts;
    }

    /**
     * @param array  $product
     * @param string $skuIndexKey
     * @return array|\array[]
     */
    protected function getSubProductsBySkuIndex(array $product, $skuIndexKey)
    {
        if (!isset($product[$skuIndexKey])) {
            return [];
        }
        $subProductSkus = $product[$skuIndexKey];
        $subProductSkus = explode(',', $subProductSkus);
        $subProducts = $this->getProductDataBySkus($subProductSkus);

        return $subProducts;
    }

    /**
     * @param $product
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
