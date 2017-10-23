<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorBusinessFactory getFactory()
 */
class ProductOptionCartConnectorFacade extends AbstractFacade implements ProductOptionCartConnectorFacadeInterface
{
    /**
     *
     * Specification:
     *  - Expands product option transfer object with additional data from persistence
     *  - Returns CartChangeTransfer transfer with option data included
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandProductOptions(CartChangeTransfer $changeTransfer)
    {
        return $this->getFactory()
            ->createProductOptionValueExpander()
            ->expandProductOptions($changeTransfer);
    }

    /**
     *
     * Specification:
     *  - Sets group key to itemTransfer to contain product option identifiers.
     *  - Returns CartChangeTransfer with modified group key for each item, which includes options
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandGroupKey(CartChangeTransfer $changeTransfer)
    {
        return $this->getFactory()
            ->createGroupKeyExpander()
            ->expand($changeTransfer);
    }

    /**
     *
     * Specification:
     *  - Sets each product quantity to item quantity
     *  - Returns CartChangeTransfer with modified item quantity
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeProductOptionInCartQuantity(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createProductOptionCartQuantity()
            ->changeQuantity($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkProductOptionExists(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createProductOptionExistsPreCheck()
            ->checkProductOptionExists($cartChangeTransfer);
    }
}
