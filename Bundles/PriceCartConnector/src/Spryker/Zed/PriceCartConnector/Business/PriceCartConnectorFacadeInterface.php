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
     * Specification:
     *  - Adds product prices to item, based on currency, price mode and price type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     * @param string|null $grossPriceType
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addPriceToItems(CartChangeTransfer $change, $grossPriceType = null);

    /**
     * Specification:
     *  - Validates product prices, checks if prices are valid for current currency, price mode, price type combination
     *  - Writes error message to response transfer if not valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validatePrices(CartChangeTransfer $cartChangeTransfer);
}
