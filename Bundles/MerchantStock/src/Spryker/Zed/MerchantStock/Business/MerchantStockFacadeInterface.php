<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Business;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;

interface MerchantStockFacadeInterface
{
    /**
     * Specification:
     * - Creates new stock for the provided merchant.
     * - Returns MerchantResponseTransfer.isSuccessful=false and error messages if merchant stock creating is not valid.
     * - Returns MerchantResponseTransfer.isSuccessful=true and MerchantResponseTransfer.merchant.stocks is add from newly created entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createMerchantStockByMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * Specification:
     * - Returns StockCollectionTransfer with stocks related to provider merchant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function getStockCollectionByMerchant(MerchantTransfer $merchantTransfer): StockCollectionTransfer;
}
