<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 */
class OrderAmendmentCartReorderValidatorPlugin extends AbstractPlugin implements CartReorderValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.order.orderReference` to be set.
     * - Does nothing if `CartReorderTransfer.quote.amendmentOrderReference` is not set.
     * - Validates if `CartReorderTransfer.quote.amendmentOrderReference` matches `CartReorderTransfer.order.orderReference`.
     * - Returns `ErrorCollectionTransfer` with error messages if validation fails.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validate(
        CartReorderTransfer $cartReorderTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer {
        return $this->getFacade()->validateCartReorder($cartReorderTransfer, $cartReorderResponseTransfer);
    }
}
