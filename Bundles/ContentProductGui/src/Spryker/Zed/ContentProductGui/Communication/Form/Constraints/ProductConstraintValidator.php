<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Form\Constraints;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use InvalidArgumentException;
use Spryker\Zed\ContentProductGui\Communication\Form\ProductAbstractListContentTermForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductConstraintValidator extends ConstraintValidator
{
    /**
     * @param array $abstractProductIds The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\SprykerShop\Yves\QuickOrderPage\Form\Constraint\ItemsFieldConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($abstractProductIds, Constraint $constraint)
    {
        if (!$constraint instanceof ProductConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                ProductConstraint::class,
                get_class($constraint)
            ));
        }

        $contentProductAbstractListTermTransfer = new ContentProductAbstractListTermTransfer();
        $contentProductAbstractListTermTransfer->setIdProductAbstracts($abstractProductIds);

        $contentValidationResponseTransfer = $constraint
            ->getContentProductFacade()
            ->validateContentProductAbstractListTerm($contentProductAbstractListTermTransfer);

        if (!$contentValidationResponseTransfer->getIsSuccess()) {
            foreach ($contentValidationResponseTransfer->getParameterMessages() as $parametrMessage) {
                foreach ($parametrMessage->getMessages() as $message) {
                    $text = strtr($message->getValue(), $message->getParameters());
                    $this->context
                        ->buildViolation($constraint->getMessage() . ' ' . $text)
                        ->atPath(ProductAbstractListContentTermForm::FIELD_ID_ABSTRACT_PRODUCTS)
                        ->addViolation();
                }
            }
        }
    }
}
