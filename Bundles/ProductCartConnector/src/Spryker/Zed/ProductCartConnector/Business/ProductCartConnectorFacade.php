<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorBusinessFactory getFactory()
 */
class ProductCartConnectorFacade extends AbstractFacade implements ProductCartConnectorFacadeInterface
{
    /**
     * Specification:
     * - Reads a persisted concrete product from database.
     * - Expands the items of the CartChangeTransfer with the concrete product's data.
     * - Returns the expanded CartChangeTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createProductExpander()
            ->expandItems($cartChangeTransfer);
    }

    /**
     * Specification:
     * - Checks added to cart products on existing
     * - Returns pre-check transfer with error messages (in negative case)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createProductValidator()
            ->validateItems($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createInactiveItemsFilter()
            ->filterInactiveItems($quoteTransfer);
    }
}
