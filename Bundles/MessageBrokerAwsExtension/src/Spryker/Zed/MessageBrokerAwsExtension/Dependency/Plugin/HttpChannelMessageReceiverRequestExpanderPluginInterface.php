<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAwsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\HttpRequestTransfer;

/**
 * Provides extension capabalities for `HttpRequest` transfer which is used to do an authentication request.
 *
 * Use this plugin if some properties of `HttpRequest` transfer should be added, for example to be used like headers for authentication request.
 */
interface HttpChannelMessageReceiverRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `HttpRequest` transfer by including data to request an API gateway specifically for HTTP channels.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    public function expand(HttpRequestTransfer $httpRequestTransfer): HttpRequestTransfer;
}
