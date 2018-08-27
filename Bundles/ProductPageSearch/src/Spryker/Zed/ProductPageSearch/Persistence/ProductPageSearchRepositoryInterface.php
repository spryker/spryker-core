<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

interface ProductPageSearchRepositoryInterface
{
    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer[]
     */
    public function findProductConcreteSearchPagesByIds(array $ids): array;

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function findConcreteProductsByIds(array $ids): array;
}
