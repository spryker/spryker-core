<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator\Rule\CategoryClosureTable;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface CategoryClosureTableValidatorRuleInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $categoryNodeTransfers): ErrorCollectionTransfer;
}
