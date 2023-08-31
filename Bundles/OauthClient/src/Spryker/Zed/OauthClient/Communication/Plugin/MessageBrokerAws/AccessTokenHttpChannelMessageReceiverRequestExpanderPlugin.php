<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Communication\Plugin\MessageBrokerAws;

use Generated\Shared\Transfer\HttpRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerAwsExtension\Dependency\Plugin\HttpChannelMessageReceiverRequestExpanderPluginInterface;

/**
 * @method \Spryker\Zed\OauthClient\Business\OauthClientFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthClient\OauthClientConfig getConfig()
 */
class AccessTokenHttpChannelMessageReceiverRequestExpanderPlugin extends AbstractPlugin implements HttpChannelMessageReceiverRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `HttpRequest` transfer by including an `Authorization` header containing the access token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    public function expand(HttpRequestTransfer $httpRequestTransfer): HttpRequestTransfer
    {
        return $this->getFacade()->expandHttpChannelMessageReceiverRequest($httpRequestTransfer);
    }
}
