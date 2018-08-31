<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader;

interface ProductConcretePageSearchReaderInterface
{
    /**
     * @return array
     */
    public function findAllProductConcretePageSearchEntities(): array;

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function findProductConcretePageSearchEntitiesByProductConcreteIds(array $ids): array;
}
