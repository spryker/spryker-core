<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Business\Deleter;

use Generated\Shared\Transfer\NopaymentPaidCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\NopaymentPaidCollectionResponseTransfer;

interface NopaymentPaidDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\NopaymentPaidCollectionDeleteCriteriaTransfer $nopaymentPaidCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NopaymentPaidCollectionResponseTransfer
     */
    public function deleteNopaymentPaidCollection(
        NopaymentPaidCollectionDeleteCriteriaTransfer $nopaymentPaidCollectionDeleteCriteriaTransfer
    ): NopaymentPaidCollectionResponseTransfer;
}
