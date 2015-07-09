<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model;

use SprykerFeature\Client\Catalog\Service\Model\Exception\ProductNotFoundException;

interface CatalogInterface
{

    /**
     * @param int $id
     *
     * @throws ProductNotFoundException
     *
     * @return array
     */
    public function getProductDataById($id);

    /**
     * @param array       $ids
     * @param string|null $indexByKey
     *
     * @throws ProductNotFoundException
     *
     * @return array[]
     */
    public function getProductDataByIds(array $ids, $indexByKey = null);

    /**
     * @param array $product
     *
     * @return array
     */
    public function getSubProducts(array $product);

}
