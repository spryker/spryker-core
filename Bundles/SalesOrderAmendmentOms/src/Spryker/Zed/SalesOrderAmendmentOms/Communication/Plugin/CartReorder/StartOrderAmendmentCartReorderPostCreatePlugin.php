<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPostReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 */
class StartOrderAmendmentCartReorderPostCreatePlugin extends AbstractPlugin implements CartPostReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `CartReorderTransfer.quoteTransfer.amendmentOrderReference` is not set.
     * - Triggers OMS event defined in {@link \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig::getStartOrderAmendmentEvent()} to start the order amendment process.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function postReorder(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $this->getFacade()->startOrderAmendment($cartReorderTransfer);

        return $cartReorderTransfer;
    }
}
