<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Form\Constraints;

use Generated\Shared\Transfer\ContentFileListTermTransfer;
use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use InvalidArgumentException;
use Spryker\Zed\ContentFileGui\Communication\Form\FileListContentTermForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContentFileListConstraintValidator extends ConstraintValidator
{
    /**
     * @param array<int> $value File ids that should be validated
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ContentFileGui\Communication\Form\Constraints\ContentFileListConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContentFileListConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                ContentFileListConstraint::class,
                get_class($constraint),
            ));
        }

        $contentFileListTermTransfer = new ContentFileListTermTransfer();
        $contentFileListTermTransfer->setFileIds($value);

        $contentValidationResponseTransfer = $constraint
            ->getContentFileFacade()
            ->validateContentFileListTerm($contentFileListTermTransfer);

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
                ->buildViolation($messageTransfer->getValueOrFail())
                ->atPath(FileListContentTermForm::FIELD_FILE_IDS);

            foreach ($messageTransfer->getParameters() as $parameter => $value) {
                $constraintViolation->setParameter($parameter, $value);
            }

            $constraintViolation->addViolation();
        }
    }
}
