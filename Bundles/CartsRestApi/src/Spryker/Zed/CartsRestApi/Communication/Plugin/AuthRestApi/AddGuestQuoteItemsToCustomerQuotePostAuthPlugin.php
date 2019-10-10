<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Communication\Plugin\AuthRestApi;

use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Zed\AuthRestApiExtension\Dependency\Plugin\PostAuthPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class AddGuestQuoteItemsToCustomerQuotePostAuthPlugin extends AbstractPlugin implements PostAuthPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds items from guest quote to customer quote.
     * - Reads anonymous customer quote.
     * - Reads registered customer quote.
     * - Aborts if anonymous customer reference or customer reference are not set on the OauthResponseTransfer.
     * - Aborts if guest customer quote is not found or is empty.
     * - Adds all guest cart items to the customer quote.
     * - Deletes guest quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return void
     */
    public function postAuth(OauthResponseTransfer $oauthResponseTransfer): void
    {
        $this->getFacade()->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
    }
}
