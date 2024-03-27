<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;

interface MerchantRelationshipDashboardGuiTableDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return list<array<string, string|int|bool>>
     */
    public function fetchData(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): array;
}
