<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentDashboardMerchantPortalGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GuiTableDataResponseTransfer;

/**
 * Implement this plugin interface to expand merchant user table data.
 */
interface MerchantUserTableDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided `GuiTableDataResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expand(GuiTableDataResponseTransfer $guiTableDataResponseTransfer): GuiTableDataResponseTransfer;
}
