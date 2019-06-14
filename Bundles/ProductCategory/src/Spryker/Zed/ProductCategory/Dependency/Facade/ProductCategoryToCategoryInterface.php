<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductCategoryToCategoryInterface
{
    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function touchCategoryActive($idCategory);

    /**
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getNodePath(int $idNode, LocaleTransfer $localeTransfer): string;

    /**
     * @return string
     */
    public function getCategoryListUrl(): string;
}
