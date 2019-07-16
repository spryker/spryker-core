<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer;

class ProductOptionMapper
{
    /**
     * @param array $productAbstractOptionGroupStatuses
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer[]
     */
    public function mapProductAbstractOptionGroupStatusesToTransfers(
        array $productAbstractOptionGroupStatuses
    ): array {
        $productAbstractOptionGroupStatusTransfers = [];
        foreach ($productAbstractOptionGroupStatuses as $productAbstractOptionGroupStatus) {
            $productAbstractOptionGroupStatusTransfers[] = $this->mapProductAbstractOptionGroupStatusToTransfer(
                $productAbstractOptionGroupStatus
            );
        }

        return $productAbstractOptionGroupStatusTransfers;
    }

    /**
     * @param array $productAbstractOptionGroupStatus
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer
     */
    protected function mapProductAbstractOptionGroupStatusToTransfer(
        array $productAbstractOptionGroupStatus
    ): ProductAbstractOptionGroupStatusTransfer {
        return (new ProductAbstractOptionGroupStatusTransfer())
            ->fromArray($productAbstractOptionGroupStatus);
    }
}
