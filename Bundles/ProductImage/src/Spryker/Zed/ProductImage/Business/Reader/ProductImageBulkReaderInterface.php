<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Reader;

interface ProductImageBulkReaderInterface
{
    /**
     * @param array<int> $productIds
     * @param string $productImageSetName
     *
     * @return array<\Generated\Shared\Transfer\ProductImageTransfer[]>
     */
    public function getProductImagesByProductIdsAndProductImageSetName(array $productIds, string $productImageSetName): array;
}
