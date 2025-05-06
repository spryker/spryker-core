<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderRequestValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsBusinessFactory getBusinessFactory()
 */
class IsAmendableOrderCartReorderRequestValidatorPlugin extends AbstractPlugin implements CartReorderRequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `CartReorderRequestTransfer.isAmendment` is not `true`.
     * - Requires `CartReorderRequestTransfer.orderReference` to be set.
     * - Validates if all order items are in order item state that has `amendable` flag.
     * - Returns `CartReorderResponseTransfer` with error messages if validation fails.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validate(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer {
        return $this->getBusinessFactory()
            ->createCartReorderRequestValidator()
            ->validate($cartReorderRequestTransfer, $cartReorderResponseTransfer);
    }
}
