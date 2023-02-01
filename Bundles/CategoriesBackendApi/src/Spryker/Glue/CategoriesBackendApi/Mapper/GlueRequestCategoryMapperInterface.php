<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Mapper;

use Generated\Shared\Transfer\CategoryCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\CategoryCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;

interface GlueRequestCategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCriteriaTransfer
     */
    public function mapGlueRequestTransferToCategoryCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionDeleteCriteriaTransfer
     */
    public function mapGlueRequestTransferToCategoryCollectionDeleteCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCollectionDeleteCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCriteriaTransfer
     */
    public function mapGlueGetRequestToCategoryCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionDeleteCriteriaTransfer
     */
    public function mapGlueRequestToCategoryCollectionDeleteCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCollectionDeleteCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionRequestTransfer
     */
    public function mapGlueRequestToCategoryCollectionRequestTransfer(
        CategoryTransfer $categoryTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCollectionRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCriteriaTransfer
     */
    public function mapGlueGetCollectionRequestToCategoryCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): CategoryCriteriaTransfer;
}
