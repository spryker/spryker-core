<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthClient\Business\OauthClientFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthClient\OauthClientConfig getConfig()
 */
class AccessTokenMessageAttributeProviderPlugin extends AbstractPlugin implements MessageAttributeProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves an access token from an access token provider by AccessTokenRequestTransfer.
     * - Throws exception `AccessTokenNotFoundException` in case if `AccessTokenResponseTransfer::isSuccessful = false`.
     * - Updates the `MessageAttributes.authorization` property with the received access token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function provideMessageAttributes(MessageAttributesTransfer $messageAttributesTransfer): MessageAttributesTransfer
    {
        return $this->getFacade()->expandMessageAttributes($messageAttributesTransfer);
    }
}
