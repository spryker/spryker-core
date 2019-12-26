<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Resolver;

use ArrayObject;

interface ProductImageSetResolverInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     * @param string $localeName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function resolveProductImageSetsForLocale(ArrayObject $productImageSetTransfers, string $localeName): ArrayObject;
}
