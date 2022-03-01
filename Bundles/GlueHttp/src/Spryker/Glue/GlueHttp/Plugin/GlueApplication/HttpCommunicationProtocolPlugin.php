<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueHttp\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueHttp\GlueHttpFactory getFactory()
 */
class HttpCommunicationProtocolPlugin extends AbstractPlugin implements CommunicationProtocolPluginInterface
{
    /**
     * {@inheritDoc}
     * - Always returns `true` since HTTP is the default protocol for Glue API.
     *
     * @api
     *
     * @return bool
     */
    public function isApplicable(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * - Extracts HTTP-specific parameters into `GlueRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extractRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        return $this->getFactory()->createRequestBuilder()->extract($glueRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Sends HTTP response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return void
     */
    public function sendResponse(GlueResponseTransfer $glueResponseTransfer): void
    {
        $this->getFactory()->createHttpSender()->sendResponse($glueResponseTransfer);
    }
}
