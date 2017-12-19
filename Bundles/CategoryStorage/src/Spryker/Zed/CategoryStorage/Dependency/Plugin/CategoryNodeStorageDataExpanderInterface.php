<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Dependency\Plugin;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

interface CategoryNodeStorageDataExpanderInterface
{

    /**
     * @param CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return CategoryNodeStorageTransfer
     */
    public function expandCategoryNodeStorageData(CategoryNodeStorageTransfer $categoryNodeStorageTransfer);
}
