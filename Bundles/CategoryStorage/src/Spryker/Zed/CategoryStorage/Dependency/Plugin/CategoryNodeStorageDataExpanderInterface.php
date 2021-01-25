<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Dependency\Plugin;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

/**
 * @deprecated Will be removed with next major release.
 */
interface CategoryNodeStorageDataExpanderInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function expandCategoryNodeStorageData(CategoryNodeStorageTransfer $categoryNodeStorageTransfer);
}
