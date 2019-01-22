<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Communication\Controller;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuidAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->findQuoteByUuid($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuoteAction(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateQuote($restQuoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteAction(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->createQuote($restQuoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuoteAction(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->deleteQuote($restQuoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createGuestQuoteAction(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->createGuestQuote($restQuoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateGuestQuoteAction(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateGuestQuote($restQuoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateItemQuantityAction(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateItemQuantity($restQuoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItemAction(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->addItem($restQuoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteItemAction(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->deleteItem($restQuoteRequestTransfer);
    }
}
