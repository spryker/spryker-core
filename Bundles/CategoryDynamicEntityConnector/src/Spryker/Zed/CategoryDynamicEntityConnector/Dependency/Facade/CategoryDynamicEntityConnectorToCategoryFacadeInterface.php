<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade;

use Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer;
use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer;

interface CategoryDynamicEntityConnectorToCategoryFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryCollection(CategoryCriteriaTransfer $categoryCriteriaTransfer): CategoryCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer
     */
    public function createCategoryUrlCollection(
        CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
    ): CategoryUrlCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer
     */
    public function updateCategoryUrlCollection(
        CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
    ): CategoryUrlCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer
     */
    public function createCategoryClosureTableCollection(
        CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
    ): CategoryClosureTableCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer
     */
    public function updateCategoryClosureTableCollection(
        CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
    ): CategoryClosureTableCollectionResponseTransfer;
}
