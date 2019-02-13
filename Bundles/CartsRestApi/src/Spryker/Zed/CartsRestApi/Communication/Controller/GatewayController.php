<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Communication\Controller;

use Generated\Shared\Transfer\AssigningGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
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
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getCustomerQuoteCollectionAction(
        RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
    ): QuoteCollectionResponseTransfer {
        return $this->getFacade()->getCustomerQuoteCollection($restQuoteCollectionRequestTransfer);
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
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateItemAction(RestCartItemRequestTransfer $restCartItemRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateItem($restCartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItemAction(RestCartItemRequestTransfer $restCartItemRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->addItem($restCartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteItemAction(RestCartItemRequestTransfer $restCartItemRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->deleteItem($restCartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItemToGuestCartAction(RestCartItemRequestTransfer $restCartItemRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->addItemToGuestCart($restCartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssigningGuestQuoteRequestTransfer $assigningGuestQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function assignGuestCartToRegisteredCustomerAction(AssigningGuestQuoteRequestTransfer $assigningGuestQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->assignGuestCartToRegisteredCustomer($assigningGuestQuoteRequestTransfer);
    }
}
