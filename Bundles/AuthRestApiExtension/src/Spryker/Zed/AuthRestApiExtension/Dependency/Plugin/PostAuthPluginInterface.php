<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer;

interface PostAuthPluginInterface
{
    /**
     * Specification:
     *  - Executes after customer is logged in via REST API.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer
     *
     * @return void
     */
    public function postAuth(AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer): void;
}
