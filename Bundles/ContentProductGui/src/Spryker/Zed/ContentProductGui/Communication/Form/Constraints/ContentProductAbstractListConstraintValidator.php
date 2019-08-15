<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Form\Constraints;

use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use InvalidArgumentException;
use Spryker\Zed\ContentProductGui\Communication\Form\ProductAbstractListContentTermForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContentProductAbstractListConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $abstractProductIds The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ContentProductGui\Communication\Form\Constraints\ContentProductAbstractListConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($abstractProductIds, Constraint $constraint): void
    {
        if (!$constraint instanceof ContentProductAbstractListConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                ContentProductAbstractListConstraint::class,
                get_class($constraint)
            ));
        }

        $contentProductAbstractListTermTransfer = new ContentProductAbstractListTermTransfer();
        $contentProductAbstractListTermTransfer->setIdProductAbstracts($abstractProductIds);

        $contentValidationResponseTransfer = $constraint
            ->getContentProductFacade()
            ->validateContentProductAbstractListTerm($contentProductAbstractListTermTransfer);

        if (!$contentValidationResponseTransfer->getIsSuccess()) {
            foreach ($contentValidationResponseTransfer->getParameterMessages() as $parameterMessage) {
                $this->addViolations($parameterMessage);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ContentParameterMessageTransfer $parameterMessageTransfer
     *
     * @return void
     */
    protected function addViolations(ContentParameterMessageTransfer $parameterMessageTransfer): void
    {
        foreach ($parameterMessageTransfer->getMessages() as $messageTransfer) {
            $constraintViolation = $this->context
                ->buildViolation($messageTransfer->getValue())
                ->atPath(ProductAbstractListContentTermForm::FIELD_ID_ABSTRACT_PRODUCTS);
            foreach ($messageTransfer->getParameters() as $parameter => $value) {
                $constraintViolation->setParameter($parameter, $value);
            }
            $constraintViolation->addViolation();
        }
    }
}
