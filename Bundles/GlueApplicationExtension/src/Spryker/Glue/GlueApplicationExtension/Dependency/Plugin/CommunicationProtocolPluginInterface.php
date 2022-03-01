<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

/**
 * Use this plugin for to implement an alternative communication protocol that can be used by API applications.
 */
interface CommunicationProtocolPluginInterface
{
    /**
     * Specification:
     * - Decides whether the communication protocol is applicable for the current request.
     *
     * @api
     *
     * @return bool
     */
    public function isApplicable(): bool;

    /**
     * Specification:
     * - Extracts request data from protocol to the `GlueRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extractRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer;

    /**
     * Specification:
     * - Sends the communication protocol-specific response based on `GlueResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return void
     */
    public function sendResponse(GlueResponseTransfer $glueResponseTransfer): void;
}
