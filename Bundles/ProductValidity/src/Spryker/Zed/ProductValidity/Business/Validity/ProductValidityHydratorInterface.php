<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Business\Validity;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;

interface ProductValidityHydratorInterface
{
    /**
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return ProductConcreteTransfer
     */
    public function hydrate(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
