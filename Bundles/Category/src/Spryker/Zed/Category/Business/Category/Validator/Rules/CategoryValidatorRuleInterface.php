<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Category\Validator\Rules;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryValidatorRuleInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array<string>
     */
    public function validate(CategoryTransfer $categoryTransfer): array;
}
