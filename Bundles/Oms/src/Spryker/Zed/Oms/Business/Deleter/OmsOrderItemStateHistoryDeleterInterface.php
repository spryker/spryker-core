<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Deleter;

use Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionResponseTransfer;

interface OmsOrderItemStateHistoryDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer $omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionResponseTransfer
     */
    public function deleteOmsOrderItemStateHistoryCollection(
        OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer $omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer
    ): OmsOrderItemStateHistoryCollectionResponseTransfer;
}
