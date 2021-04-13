<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockCategoryReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function hydrateCategoryRelations(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return string[]
     */
    public function getRenderedCategoryList($idCmsBlock, $idLocale);

    /**
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlockCollection($idCategory, $idCategoryTemplate);

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return string[]
     */
    public function getCmsBlockNamesIndexedByCmsBlockIdsForCategory(CategoryTransfer $categoryTransfer): array;
}
