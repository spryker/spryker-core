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
class UpdateGuestQuoteToCustomerQuotePostAuthPlugin extends AbstractPlugin implements PostAuthPluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates non-empty guest quote to new customer quote.
     * - OauthResponseTransfer.customerReference and OauthResponseTransfer.anonymousCustomerReference must be set.
     * - Anonymous customer has to have a cart.
     * - Anonymous customer's cart has to contain items. Otherwise method terminates without errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return void
     */
    public function postAuth(OauthResponseTransfer $oauthResponseTransfer): void
    {
        $this->getFacade()->convertGuestQuoteToCustomerQuote($oauthResponseTransfer);
    }
}
