<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\ProductConcreteReader;

interface ProductConcreteReaderInterface
{
    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function findProductConcreteByIds(array $ids): array;

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function findAllProductConcrete(): array;
}
