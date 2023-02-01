<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Mapper;

use Generated\Shared\Transfer\CategoryCollectionResponseTransfer;
use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface GlueResponseCategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapCategoryCollectionTransferToGlueResponseTransfer(
        CategoryCollectionTransfer $categoryCollectionTransfer
    ): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapCategoryCollectionTransferToSingleResourceGlueResponseTransfer(
        CategoryCollectionTransfer $categoryCollectionTransfer
    ): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapCategoryCollectionResponseTransferToGlueResponseTransfer(
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapCategoryCollectionResponseTransferToSingleResourceGlueResponseTransfer(
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): GlueResponseTransfer;
}
