<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Product;

interface ProductAttributeReaderInterface
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
     * @throws \Spryker\Zed\ProductAttribute\Business\Model\Exception\ProductAbstractNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function getProductAbstractTransfer($idProductAbstract);

    /**
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\ProductAttribute\Business\Model\Exception\ProductConcreteNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function getProductTransfer($idProduct);

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText = '', $limit = 10);

}
