<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator;

use Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer;

interface CategoryNodeValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer
     */
    public function validate(
        CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer
    ): CategoryNodeCollectionResponseTransfer;
}
