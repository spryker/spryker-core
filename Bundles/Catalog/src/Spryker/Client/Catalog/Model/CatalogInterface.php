<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Catalog\Model;

interface CatalogInterface
{

    /**
     * @param int $id
     *
     * @throws \Spryker\Client\Catalog\Model\Exception\ProductNotFoundException
     *
     * @return array
     */
    public function getProductDataById($id);

    /**
     * @param array $ids
     * @param string|null $indexByKey
     *
     * @throws \Spryker\Client\Catalog\Model\Exception\ProductNotFoundException
     *
     * @return array
     */
    public function getProductDataByIds(array $ids, $indexByKey = null);

    /**
     * @param array $product
     *
     * @return array
     */
    public function getSubProducts(array $product);

}
