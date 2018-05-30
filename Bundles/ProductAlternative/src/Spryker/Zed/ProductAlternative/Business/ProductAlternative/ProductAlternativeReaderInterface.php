<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductAlternativeReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesByIdProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductAlternativeCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAlternativeByIdProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTransfer;
}
