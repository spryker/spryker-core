<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator\Rule\CategoryUrl;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface CategoryUrlValidatorRuleInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer> $categoryTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $categoryTransfers): ErrorCollectionTransfer;
}
