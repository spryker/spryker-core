<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;

interface ProductAlternativeListHydratorInterface
{
    /**
     * @param int $idProductAbstractAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function hydrateProductAbstractListItem(
        int $idProductAbstractAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer;

    /**
     * @param int $idProductConcreteAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function hydrateProductConcreteListItem(
        int $idProductConcreteAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer;
}
