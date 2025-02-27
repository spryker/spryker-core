<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Deleter;

use Generated\Shared\Transfer\OmsTransitionLogCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\OmsTransitionLogCollectionResponseTransfer;

interface OmsTransitionLogDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsTransitionLogCollectionDeleteCriteriaTransfer $omsTransitionLogCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsTransitionLogCollectionResponseTransfer
     */
    public function deleteOmsTransitionLogCollection(
        OmsTransitionLogCollectionDeleteCriteriaTransfer $omsTransitionLogCollectionDeleteCriteriaTransfer
    ): OmsTransitionLogCollectionResponseTransfer;
}
