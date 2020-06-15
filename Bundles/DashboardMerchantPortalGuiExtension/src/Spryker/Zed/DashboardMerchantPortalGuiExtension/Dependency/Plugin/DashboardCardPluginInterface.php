<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DashboardMerchantPortalGuiExtension\Dependency\Plugin;

interface DashboardCardPluginInterface
{
    /**
     * Specification:
     * - Returns HTML for the card title.
     *
     * @api
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Specification:
     * - Returns HTML for the card content.
     *
     * @api
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Specification:
     * - Returns array of DashboardActionButton transfers.
     * - Each DashboardActionButton transfer contains the data for displaying the corresponding button.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DashboardActionButtonTransfer[]
     */
    public function getActionButtons(): array;
}
