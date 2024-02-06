<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\Mapper;

use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\MerchantUserCollectionTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTableCriteriaTransfer;

interface MerchantUserGuiTableMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserTableCriteriaTransfer $merchantUserTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserCriteriaTransfer
     */
    public function mapMerchantUserTableCriteriaTransferToMerchantUserCriteriaTransfer(
        MerchantUserTableCriteriaTransfer $merchantUserTableCriteriaTransfer,
        MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
    ): MerchantUserCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCollectionTransfer $merchantUserCollectionTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function mapMerchantUserCollectionTransferToGuiTableDataResponseTransfer(
        MerchantUserCollectionTransfer $merchantUserCollectionTransfer,
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): GuiTableDataResponseTransfer;
}
