<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\Category\Business\Validator\Rule\CategoryNode\CategoryNodeValidatorRuleInterface;
use Spryker\Zed\Category\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class CategoryNodeValidator implements CategoryNodeValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\Category\Business\Validator\Rule\CategoryNode\CategoryNodeValidatorRuleInterface>
     */
    protected array $categoryNodeValidatorRules;

    /**
     * @param list<\Spryker\Zed\Category\Business\Validator\Rule\CategoryNode\CategoryNodeValidatorRuleInterface> $categoryNodeValidatorRules
     */
    public function __construct(array $categoryNodeValidatorRules)
    {
        $this->categoryNodeValidatorRules = $categoryNodeValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer
     */
    public function validate(
        CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer
    ): CategoryNodeCollectionResponseTransfer {
        foreach ($this->categoryNodeValidatorRules as $categoryNodeValidatorRule) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers */
            $categoryNodeTransfers = $categoryNodeCollectionResponseTransfer->getCategoryNodes();

            $errorCollectionTransfer = $categoryNodeValidatorRule->validate($categoryNodeTransfers);

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers */
            $initialErrorTransfers = $categoryNodeCollectionResponseTransfer->getErrors();

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers */
            $postValidationErrorTransfers = $errorCollectionTransfer->getErrors();

            $categoryNodeCollectionResponseTransfer = $this->mergeErrors(
                $categoryNodeCollectionResponseTransfer,
                $errorCollectionTransfer,
            );

            if ($this->isValidationTerminated($categoryNodeValidatorRule, $initialErrorTransfers, $postValidationErrorTransfers)) {
                break;
            }
        }

        return $categoryNodeCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\Category\Business\Validator\Rule\CategoryNode\CategoryNodeValidatorRuleInterface $categoryNodeValidatorRule
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    protected function isValidationTerminated(
        CategoryNodeValidatorRuleInterface $categoryNodeValidatorRule,
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        if (!$categoryNodeValidatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return $categoryNodeValidatorRule->isTerminated($initialErrorTransfers, $postValidationErrorTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer
     */
    protected function mergeErrors(
        CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): CategoryNodeCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $categoryNodeCollectionResponseErrorTransfers */
        $categoryNodeCollectionResponseErrorTransfers = $categoryNodeCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $categoryNodeCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $categoryNodeCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
