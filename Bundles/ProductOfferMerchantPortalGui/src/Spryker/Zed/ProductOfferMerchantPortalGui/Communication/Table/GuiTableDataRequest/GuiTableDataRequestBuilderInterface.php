<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Symfony\Component\HttpFoundation\Request;

interface GuiTableDataRequestBuilderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    public function buildGuiTableDataRequest(Request $request, GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableDataRequestTransfer;
}
