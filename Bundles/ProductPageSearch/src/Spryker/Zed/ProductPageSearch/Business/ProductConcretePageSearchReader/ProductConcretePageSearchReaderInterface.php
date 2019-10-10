<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductConcretePageSearchReaderInterface
{
    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfersByProductIds(array $productIds): array;

    /**
     * Specification:
     * - Returns array with following structure:
     * - [
     *     'DE' => [
     *       'en_US' => \Generated\Shared\Transfer\ProductConcretePageSearchTransfer,
     *       'de_DE' => \Generated\Shared\Transfer\ProductConcretePageSearchTransfer,
     *     ]
     *   ]
     *
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductConcretePageSearchTransfersByProductIdsGrouppedByStoreAndLocale(array $productConcreteIds): array;

    /**
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfersByProductAbstractStoreMap(array $productAbstractStoreMap): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function getProductEntityByFilter(FilterTransfer $filterTransfer): array;
}
