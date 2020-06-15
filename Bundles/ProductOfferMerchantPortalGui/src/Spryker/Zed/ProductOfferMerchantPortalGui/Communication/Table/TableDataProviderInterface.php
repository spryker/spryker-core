<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Symfony\Component\HttpFoundation\Request;

interface TableDataProviderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfiguration
     *
     * @return mixed
     */
    public function getData(Request $request, GuiTableConfigurationTransfer $guiTableConfiguration);
}
