<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductAlternativeManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternatives(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;

    /**
     * @param int $idBaseProduct
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ProductAlternativeResponseTransfer;

    /**
     * @param int $idBaseProduct
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ProductAlternativeResponseTransfer;
}
