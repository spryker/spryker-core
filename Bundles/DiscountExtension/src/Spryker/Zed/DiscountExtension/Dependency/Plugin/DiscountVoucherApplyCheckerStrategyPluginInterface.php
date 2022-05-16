<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Provides extension capabilities to make decision if voucher code is applied.
 */
interface DiscountVoucherApplyCheckerStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if the plugin can be used.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer, string $voucherCode): bool;

    /**
     * Specification:
     * - Checks if voucher code is applied.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return \Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer
     */
    public function check(QuoteTransfer $quoteTransfer, string $voucherCode): DiscountVoucherCheckResponseTransfer;
}
