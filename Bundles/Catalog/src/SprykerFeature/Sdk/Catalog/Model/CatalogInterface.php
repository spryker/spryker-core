<?php

namespace SprykerFeature\Sdk\Catalog\Model;

/**
 * Class Catalog
 * @package SprykerFeature\Sdk\Catalog\Model
 */
interface CatalogInterface
{
    /**
     * @param int $id
     * @return array
     * @throws Exception\ProductNotFoundException
     */
    public function getProductDataById($id);

    /**
     * @param string $sku
     * @return array
     * @throws Exception\ProductNotFoundException
     */
    public function getProductDataBySku($sku);

    /**
     * @param array       $ids
     * @param string|null $indexByKey
     * @return array[]
     * @throws Exception\ProductNotFoundException
     */
    public function getProductDataByIds(array $ids, $indexByKey = null);

    /**
     * @param array $skus
     * @param null  $indexByKey
     * @return array
     */
    public function getProductDataBySkus(array $skus, $indexByKey = null);

    /**
     * @param array $product
     * @return array
     */
    public function getSubProducts(array $product);
}