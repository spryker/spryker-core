<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Deleter;

use Generated\Shared\Transfer\OmsEventTimeoutCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\OmsEventTimeoutCollectionResponseTransfer;

interface OmsEventTimeoutDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsEventTimeoutCollectionDeleteCriteriaTransfer $omsEventTimeoutCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsEventTimeoutCollectionResponseTransfer
     */
    public function deleteOmsEventTimeoutCollection(
        OmsEventTimeoutCollectionDeleteCriteriaTransfer $omsEventTimeoutCollectionDeleteCriteriaTransfer
    ): OmsEventTimeoutCollectionResponseTransfer;
}
