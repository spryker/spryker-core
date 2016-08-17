<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model;

/**
 * @deprecated
 */
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
