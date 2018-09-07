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
    public function findAllProductConcretePageSearchEntities(): array;

    /**
     * @param int[] $ids
     * @param bool $groupByStoreAndLocale
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function findProductConcretePageSearchEntitiesByProductConcreteIds(array $ids, bool $groupByStoreAndLocale = false): array;
}
