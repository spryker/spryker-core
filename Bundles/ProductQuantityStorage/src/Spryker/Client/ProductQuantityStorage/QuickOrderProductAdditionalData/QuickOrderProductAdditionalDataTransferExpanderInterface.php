<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\QuickOrderProductAdditionalData;

use Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer;

interface QuickOrderProductAdditionalDataTransferExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer
     */
    public function expandQuickOrderProductAdditionalDataTransferWithQuantityRestrictions(QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer): QuickOrderProductAdditionalDataTransfer;
}
