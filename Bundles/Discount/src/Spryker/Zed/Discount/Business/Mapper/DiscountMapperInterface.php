<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Mapper;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;

interface DiscountMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function mapDiscountConfiguratorTransferToDiscountTransfer(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        DiscountTransfer $discountTransfer
    ): DiscountTransfer;

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function mapDiscountTransferToDiscountConfiguratorTransfer(
        DiscountTransfer $discountTransfer,
        DiscountConfiguratorTransfer $discountConfiguratorTransfer
    ): DiscountConfiguratorTransfer;

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer $discountMoneyAmountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountMoneyAmountTransfer
     */
    public function mapMoneyValueTransferToDiscountMoneyAmountTransfer(
        MoneyValueTransfer $moneyValueTransfer,
        DiscountMoneyAmountTransfer $discountMoneyAmountTransfer
    ): DiscountMoneyAmountTransfer;

    /**
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer $discountMoneyAmountTransfer
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    public function mapDiscountMoneyAmountTransferToMoneyValueTransfer(
        DiscountMoneyAmountTransfer $discountMoneyAmountTransfer,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer;
}
