<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Reader;

interface ProductReaderInterface
{
    /**
     * @param array<string> $productAbstractSkus
     *
     * @return array<int, \Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getProductAbstractTransfersIndexedByIdProductAbstract(array $productAbstractSkus): array;
}
