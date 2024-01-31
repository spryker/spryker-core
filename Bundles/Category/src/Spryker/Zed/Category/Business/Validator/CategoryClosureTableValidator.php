<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator;

use Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\Category\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class CategoryClosureTableValidator implements CategoryClosureTableValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\Category\Business\Validator\Rule\CategoryClosureTable\CategoryClosureTableValidatorRuleInterface>
     */
    protected array $categoryClosureTableValidatorRules;

    /**
     * @param list<\Spryker\Zed\Category\Business\Validator\Rule\CategoryClosureTable\CategoryClosureTableValidatorRuleInterface> $categoryClosureTableValidatorRules
     */
    public function __construct(array $categoryClosureTableValidatorRules)
    {
        $this->categoryClosureTableValidatorRules = $categoryClosureTableValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer
     */
    public function validateCollection(
        CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
    ): CategoryClosureTableCollectionResponseTransfer {
        $NodeTransfers = $categoryClosureTableCollectionRequestTransfer->getCategoryNodes();
        $categoryClosureTableCollectionResponseTransfer = (new CategoryClosureTableCollectionResponseTransfer())
            ->setCategoryNodes($NodeTransfers);

        $initialErrorCollectionTransfer = (new ErrorCollectionTransfer());
        foreach ($this->categoryClosureTableValidatorRules as $categoryUrlValidatorRule) {
            $initialErrorCollectionTransfer->setErrors($categoryClosureTableCollectionResponseTransfer->getErrors());
            $postValidationErrorCollectionTransfer = $categoryUrlValidatorRule->validate($NodeTransfers);

            $categoryClosureTableCollectionResponseTransfer = $this->mergeErrors(
                $categoryClosureTableCollectionResponseTransfer,
                $postValidationErrorCollectionTransfer,
            );

            if ($categoryUrlValidatorRule instanceof TerminationAwareValidatorRuleInterface) {
                $categoryUrlValidatorRule->isTerminated(
                    $initialErrorCollectionTransfer->getErrors(),
                    $postValidationErrorCollectionTransfer->getErrors(),
                );

                break;
            }
        }

        return $categoryClosureTableCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer $categoryUrlCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $postValidationErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer
     */
    protected function mergeErrors(
        CategoryClosureTableCollectionResponseTransfer $categoryUrlCollectionResponseTransfer,
        ErrorCollectionTransfer $postValidationErrorCollectionTransfer
    ): CategoryClosureTableCollectionResponseTransfer {
        foreach ($postValidationErrorCollectionTransfer->getErrors() as $errorTransfer) {
            $categoryUrlCollectionResponseTransfer->addError($errorTransfer);
        }

        return $categoryUrlCollectionResponseTransfer;
    }
}
