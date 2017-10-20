<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;

interface PriceCartConnectorFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     * @param string|null $grossPriceType
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addGrossPriceToItems(CartChangeTransfer $change, $grossPriceType = null);
}
