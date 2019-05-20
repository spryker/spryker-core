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

class FileListConstraintValidator extends ConstraintValidator
{
    /**
     * @param array $fileIds The value that should be validated
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ContentFileGui\Communication\Form\Constraints\FileListConstraint $constraint The constraint for the validation
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($fileIds, Constraint $constraint): void
    {
        if (!$constraint instanceof FileListConstraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected constraint instance of %s, got %s instead.',
                FileListConstraint::class,
                get_class($constraint)
            ));
        }

        $contentFileListTermTransfer = new ContentFileListTermTransfer();
        $contentFileListTermTransfer->setFileIds($fileIds);

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
            $text = strtr($messageTransfer->getValue(), $messageTransfer->getParameters());
            $this->context
                ->buildViolation($text)
                ->atPath(FileListContentTermForm::FIELD_FILE_IDS)
                ->addViolation();
        }
    }
}
