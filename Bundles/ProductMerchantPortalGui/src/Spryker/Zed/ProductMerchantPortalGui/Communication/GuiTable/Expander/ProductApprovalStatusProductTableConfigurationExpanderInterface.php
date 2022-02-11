<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Expander;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface ProductApprovalStatusProductTableConfigurationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function expand(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer;
}
