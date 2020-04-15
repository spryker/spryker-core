<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class ReturnReasonMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $salesReturnReasonEntities
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function mapReturnReasonEntityCollectionToReturnReasonCollection(ObjectCollection $salesReturnReasonEntities): ReturnReasonCollectionTransfer
    {
        $returnReasonCollectionTransfer = new ReturnReasonCollectionTransfer();

        foreach ($salesReturnReasonEntities as $salesReturnReasonEntity) {
            $returnReasonCollectionTransfer->addReturnReason(
                (new ReturnReasonTransfer())->fromArray($salesReturnReasonEntity->toArray(), true)
            );
        }

        return $returnReasonCollectionTransfer;
    }
}
