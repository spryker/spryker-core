<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductAlternativeWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternative(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;

    /**
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAlternativeByIdProductAlternative(int $idProductAlternative): ProductAlternativeResponseTransfer;
}
