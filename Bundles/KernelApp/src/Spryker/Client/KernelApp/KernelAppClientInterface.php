<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\KernelApp;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;

/**
 * @method \Spryker\Client\KernelApp\KernelAppFactory getFactory()
 */
interface KernelAppClientInterface
{
    /**
     * Specification:
     * - Makes a request to an App.
     * - Expands the `AcpHttpResponseTransfer` with the always required X-Tenant-Identifier header.
     * - Executes AcpRequestExpanderPluginInterface's to expand the request.
     * - Returns a `AcpHttpResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function request(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpResponseTransfer;
}
