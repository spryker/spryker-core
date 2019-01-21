<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader;

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
     * @param array $storesPerAbstractProducts
     *
     * @return array
     */
    public function getProductConcretePageSearchTransfersByAbstractProductsAndStores(array $storesPerAbstractProducts): array;
}
