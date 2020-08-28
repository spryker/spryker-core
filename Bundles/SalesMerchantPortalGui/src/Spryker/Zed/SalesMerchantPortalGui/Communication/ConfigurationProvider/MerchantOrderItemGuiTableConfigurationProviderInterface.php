<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;

interface MerchantOrderItemGuiTableConfigurationProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(MerchantOrderTransfer $merchantOrderTransfer): GuiTableConfigurationTransfer;
}
