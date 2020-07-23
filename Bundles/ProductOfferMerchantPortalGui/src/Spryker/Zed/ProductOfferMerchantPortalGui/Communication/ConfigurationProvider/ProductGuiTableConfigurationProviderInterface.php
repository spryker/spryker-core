<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface ProductGuiTableConfigurationProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer;
}
