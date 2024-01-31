<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator;

use Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer;

interface CategoryClosureTableValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer
     */
    public function validateCollection(
        CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
    ): CategoryClosureTableCollectionResponseTransfer;
}
