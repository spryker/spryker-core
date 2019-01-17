<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Persistence\Propel\Mapper;

interface ProductOptionStorageMapperInterface
{
    /**
     * @param array $productOptionGroupStatuses
     * @param array $indexedProductOptionGroupStatuses
     *
     * @return array [[fkProductAbstract => [productOptionGroupName => productOptionGroupStatus]]]
     */
    public function mapProductOptionGroupStatusesToIndexedProductOptionGroupStatusesArray(array $productOptionGroupStatuses, array $indexedProductOptionGroupStatuses): array;
}
