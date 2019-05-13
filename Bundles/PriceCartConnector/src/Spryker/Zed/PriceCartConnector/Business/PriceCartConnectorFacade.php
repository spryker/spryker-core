<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorBusinessFactory getFactory()
 */
class PriceCartConnectorFacade extends AbstractFacade implements PriceCartConnectorFacadeInterface
{
    /**
     * @inheritdoc
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string|null $priceType
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addPriceToItems(CartChangeTransfer $cartChangeTransfer, $priceType = null)
    {
        return $this->getFactory()->createPriceManager()->addPriceToItems($cartChangeTransfer);
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
    public function validatePrices(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createPriceProductValidator()->validatePrices($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterItemsWithoutPrice(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createItemsWithoutPriceFilter()->filterItems($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sanitizeSourcePrices(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createSourcePriceSanitizer()
            ->sanitizeSourcePrices($quoteTransfer);
    }
}
