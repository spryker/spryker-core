<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Persistence\Mapper;

interface ProductOptionStorageMapperInterface
{
    /**
     * @param array $productOptionGroupStatuses
     *
     * @return array [[fkProductAbstract => [productOptionGroupName => productOptionGroupStatus]]]
     */
    public function mapProductOptionGroupStatusesToIndexedArray(array $productOptionGroupStatuses): array;
}
