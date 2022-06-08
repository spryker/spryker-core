<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Communication\Plugin\Payment;

use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentAuthorizeRequestExpanderPluginInterface;

/**
 * @method \Spryker\Zed\OauthClient\Business\OauthClientFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthClient\OauthClientConfig getConfig()
 */
class AccessTokenPaymentAuthorizeRequestExpanderPlugin extends AbstractPlugin implements PaymentAuthorizeRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves an access token from an access token provider by AccessTokenRequestTransfer.
     * - Throws exception `AccessTokenNotFoundException` in case if `AccessTokenResponseTransfer::isSuccessful = false`.
     * - Updates the `PaymentAuthorizeRequest.authorization` property with the received access token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer
     */
    public function expand(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
    ): PaymentAuthorizeRequestTransfer {
        return $this->getFacade()->expandPaymentAuthorizeRequest($paymentAuthorizeRequestTransfer);
    }
}
