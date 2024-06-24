<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Mapper;

use Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface MerchantCommissionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer
     */
    public function mapOrderTransferToMerchantCommissionCalculationRequestTransfer(
        OrderTransfer $orderTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): MerchantCommissionCalculationRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer $merchantCommissionCalculationItemTransfer
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>
     */
    public function mapMerchantCommissionCalculationItemTransferToSalesMerchantCommissionTransfers(
        MerchantCommissionCalculationItemTransfer $merchantCommissionCalculationItemTransfer
    ): array;
}
