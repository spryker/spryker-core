<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Builder;

interface StoreWithCurrenciesCollectionBuilderInterface
{
    /**
     * @param int $idStore
     *
     * @return array
     */
    public function buildStoreWithCurrenciesCollectionByStoreId(int $idStore): array;
}
