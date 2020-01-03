<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Business\CategoryImageStorageBusinessFactory getFactory()
 */
interface CategoryImageStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function publishCategoryImages(array $categoryIds);

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublishCategoryImages(array $categoryIds);

    /**
     * Specification:
     * - Returns CategoryImageStorageTransfer collection by filter and category ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $categoryIds
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageTransfer[]
     */
    public function getCategoryImageStorageCollectionByFilterAndCategoryIds(FilterTransfer $filterTransfer, array $categoryIds): array;
}
