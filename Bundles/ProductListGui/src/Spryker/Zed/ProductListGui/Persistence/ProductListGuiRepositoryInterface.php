<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductListGuiRepositoryInterface
{
    /**
     * @api
     *
     * @module Category
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[] [<category id> => <category name in english locale>]
     */
    public function getAllCategoriesNames(LocaleTransfer $localeTransfer): array;
}
