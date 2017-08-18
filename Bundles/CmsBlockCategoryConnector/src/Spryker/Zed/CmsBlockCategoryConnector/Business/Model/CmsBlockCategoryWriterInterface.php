<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockCategoryWriterInterface
{

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer);

}
