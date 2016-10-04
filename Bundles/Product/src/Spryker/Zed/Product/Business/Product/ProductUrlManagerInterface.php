<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductUrlManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstract);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstract);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstract);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return void
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstract);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return void
     */
    public function touchProductUrlActive(ProductAbstractTransfer $productAbstract);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return void
     */
    public function touchProductUrlDeleted(ProductAbstractTransfer $productAbstract);

}
