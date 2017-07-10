<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model;

interface AttributeReaderInterface
{

    /**
     * @param array $values
     *
     * @return array
     */
    public function getAttributesByValues(array $values);

    /**
     * @param array $values
     *
     * @return array
     */
    public function getMetaAttributesByValues(array $values);

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function getProductAbstractEntity($idProductAbstract);

    /**
     * @param int $idProduct
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    public function getProductEntity($idProduct);

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText = '', $limit = 10);

}
