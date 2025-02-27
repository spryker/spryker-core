<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Persistence;

interface ProductOptionCartConnectorRepositoryInterface
{
    /**
     * @param list<int> $productOptionValueIds
     *
     * @return list<int>
     */
    public function filterProductOptionValueIdsByActiveGroup(array $productOptionValueIds): array;
}
