<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\GuiTable\Mapper;

use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantRelationRequestGuiTableMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTableCriteriaTransfer $merchantRelationRequestTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer
     */
    public function mapMerchantRelationRequestTableCriteriaTransferToMerchantRelationRequestCriteriaTransfer(
        MerchantRelationRequestTableCriteriaTransfer $merchantRelationRequestTableCriteriaTransfer,
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer
     */
    public function mapMerchantUserTransferToMerchantRelationRequestCriteriaTransfer(
        MerchantUserTransfer $merchantUserTransfer,
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function mapMerchantRelationRequestCollectionTransferToGuiTableDataResponseTransfer(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer,
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): GuiTableDataResponseTransfer;
}
