<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;

interface CategoryMerchantCommissionConnectorToCategoryFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return array<int, list<string>>
     */
    public function getAscendantCategoryKeysGroupedByIdCategoryNode(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): array;
}
