<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Category\Validator;

use Generated\Shared\Transfer\CategoryCollectionResponseTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\Category\Business\Category\IdentifierBuilder\CategoryIdentifierBuilderInterface;

class CategoryValidator implements CategoryValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\Category\Business\Category\Validator\Rules\CategoryValidatorRuleInterface>
     */
    protected array $validatorRules = [];

    /**
     * @var \Spryker\Zed\Category\Business\Category\IdentifierBuilder\CategoryIdentifierBuilderInterface
     */
    protected CategoryIdentifierBuilderInterface $identifierBuilder;

    /**
     * @param array<\Spryker\Zed\Category\Business\Category\Validator\Rules\CategoryValidatorRuleInterface> $validatorRules
     * @param \Spryker\Zed\Category\Business\Category\IdentifierBuilder\CategoryIdentifierBuilderInterface $identifierBuilder
     */
    public function __construct(
        array $validatorRules,
        CategoryIdentifierBuilderInterface $identifierBuilder
    ) {
        $this->validatorRules = $validatorRules;
        $this->identifierBuilder = $identifierBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    public function validateCollection(
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): CategoryCollectionResponseTransfer {
        foreach ($categoryCollectionResponseTransfer->getCategories() as $categoryTransfer) {
            $categoryCollectionResponseTransfer = $this->validate($categoryTransfer, $categoryCollectionResponseTransfer);
        }

        return $categoryCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    public function validate(
        CategoryTransfer $categoryTransfer,
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): CategoryCollectionResponseTransfer {
        return $this->executeValidatorRules($categoryTransfer, $categoryCollectionResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    protected function executeValidatorRules(
        CategoryTransfer $categoryTransfer,
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): CategoryCollectionResponseTransfer {
        foreach ($this->validatorRules as $validatorRule) {
            $errors = $validatorRule->validate($categoryTransfer);

            $categoryCollectionResponseTransfer = $this->addErrorsToCollectionResponseTransfer($categoryTransfer, $categoryCollectionResponseTransfer, $errors);
        }

        return $categoryCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     * @param array<string> $errors
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    protected function addErrorsToCollectionResponseTransfer(
        CategoryTransfer $categoryTransfer,
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer,
        array $errors
    ): CategoryCollectionResponseTransfer {
        $identifier = $this->identifierBuilder->buildIdentifier($categoryTransfer);

        foreach ($errors as $error) {
            $categoryCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage($error)
                    ->setEntityIdentifier($identifier),
            );
        }

        return $categoryCollectionResponseTransfer;
    }
}
