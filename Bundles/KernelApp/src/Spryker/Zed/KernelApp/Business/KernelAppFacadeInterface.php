<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Business;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;

interface KernelAppFacadeInterface
{
    /**
     * Specification:
     * - Makes a request to an App through the KernelAppClient.
     * - Uses {@link \Spryker\Zed\KernelApp\KernelAppConfig::getDefaultHeaders()} to set default headers, if not set.
     * - Uses {@link \Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface} plugins to expand the request.
     * - Returns `AcpHttpResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function makeRequest(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpResponseTransfer;

    /**
     * Specification:
     * - Saves app config.
     * - Requires AppConfigTransfer.appIdentifier.
     * - Requires AppConfigTransfer.status.
     * - Requires AppConfigTransfer.isActive.
     * - Requires AppConfigTransfer.config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return void
     */
    public function writeAppConfig(AppConfigTransfer $appConfigTransfer): void;

    /**
     * Specification:
     * - Utilizes the grace period for App configuration from {@link \Spryker\Zed\KernelApp\KernelAppConfig::getAppConfigGracePeriod()}.
     * - The grace period delays immediate deactivation of the App configuration.
     * - An active App is that has either `active` status or `inactive` during the grace period.
     * - Queries the message channels for each active App from the database.
     * - Filters the message channels based on the obtained channel list.
     *
     * @api
     *
     * @param array $messageChannels
     *
     * @return array
     */
    public function filterMessageChannels(array $messageChannels): array;
}
