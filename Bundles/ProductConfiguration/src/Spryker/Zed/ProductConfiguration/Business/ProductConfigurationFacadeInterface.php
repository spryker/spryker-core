<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer;

interface ProductConfigurationFacadeInterface
{
    /**
     * Specification:
     * - Fetches a collection of product configurations from the storage.
     * - Uses `ProductConfigurationCriteriaTransfer.ProductConfigurationConditions.productConfigurationIds` to filter product configurations by productConfigurationIds.
     * - Uses `ProductConfigurationCriteriaTransfer.ProductConfigurationConditions.uuids` to filter product configurations by uuids.
     * - Uses `ProductConfigurationCriteriaTransfer.SortTransfer.field` to set the `order by` field.
     * - Uses `ProductConfigurationCriteriaTransfer.SortTransfer.isAscending` to set ascending order otherwise will be used descending order.
     * - Uses `ProductConfigurationCriteriaTransfer.PaginationTransfer.{limit, offset}` to paginate result with limit and offset.
     * - Uses `ProductConfigurationCriteriaTransfer.PaginationTransfer.{page, maxPerPage}` to paginate result with page and maxPerPage.
     * - Returns `ProductConfigurationCollectionTransfer` filled with found product configurations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
    ): ProductConfigurationCollectionTransfer;
}
