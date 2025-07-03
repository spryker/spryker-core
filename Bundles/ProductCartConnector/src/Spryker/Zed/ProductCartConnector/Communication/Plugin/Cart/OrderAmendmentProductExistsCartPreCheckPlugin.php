<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\CartExtension\Dependency\Plugin\TerminationAwareCartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCartConnector\Communication\ProductCartConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductCartConnector\ProductCartConnectorConfig getConfig()
 */
class OrderAmendmentProductExistsCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface, TerminationAwareCartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChangeTransfer.quote` to be set.
     * - Requires `CartChangeTransfer.quote.originalSalesOrderItems.sku` to be set.
     * - Checks added to cart products on existing.
     * - Returns pre-check transfer with error messages (in negative case).
     * - Skips items that are part of the original order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer)
    {
        $itemSkusToSkipValidation = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemSkus($cartChangeTransfer->getQuoteOrFail());

        return $this->getBusinessFactory()
            ->createProductValidator()
            ->validateItems($cartChangeTransfer, $itemSkusToSkipValidation);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function terminateOnFailure()
    {
        return true;
    }
}
