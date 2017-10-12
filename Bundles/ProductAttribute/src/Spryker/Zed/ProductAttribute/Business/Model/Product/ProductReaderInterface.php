<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Product;

interface ProductReaderInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\ProductAttribute\Business\Model\Exception\ProductAbstractNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function getProductAbstractTransfer($idProductAbstract);

    /**
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\ProductAttribute\Business\Model\Exception\ProductConcreteNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductTransfer($idProduct);
}
