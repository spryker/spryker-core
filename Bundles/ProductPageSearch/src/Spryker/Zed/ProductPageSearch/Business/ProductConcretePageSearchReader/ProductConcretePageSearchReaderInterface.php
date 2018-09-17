<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader;

interface ProductConcretePageSearchReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function findAllProductConcretePageSearchTransfers(): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function findProductConcretePageSearchTransfersByProductConcreteIds(array $productConcreteIds): array;

    /**
     * Specification:
     * - Returns array with following structure:
     * - [
     *     'DE' => [
     *       'en_US' =>  \Generated\Shared\Transfer\ProductConcretePageSearchTransfer,
     *       'de_DE' => \Generated\Shared\Transfer\ProductConcretePageSearchTransfer,
     *     ]
     *   ]
     *
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function findProductConcretePageSearchTransfersByProductConcreteIdsGrouppedByStoreAndLocale(array $productConcreteIds): array;
}
