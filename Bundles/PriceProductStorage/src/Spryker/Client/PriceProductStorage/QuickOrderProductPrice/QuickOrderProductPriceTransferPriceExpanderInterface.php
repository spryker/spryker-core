<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\QuickOrderProductPrice;

use Generated\Shared\Transfer\QuickOrderProductPriceTransfer;

interface QuickOrderProductPriceTransferPriceExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductPriceTransfer
     */
    public function expandQuickOrderProductPriceTransferWithPrice(QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer): QuickOrderProductPriceTransfer;
}
