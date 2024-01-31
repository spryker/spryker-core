<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator\Rule\CategoryNode;

use ArrayObject;
use Spryker\Zed\Category\Business\Validator\Rule\AbstractCategoryNodeExistsValidationRule;
use Spryker\Zed\Category\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class CategoryNodeExistsCategoryNodeValidationRule extends AbstractCategoryNodeExistsValidationRule implements CategoryNodeValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    public function isTerminated(
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        return $postValidationErrorTransfers->count() > $initialErrorTransfers->count();
    }
}
