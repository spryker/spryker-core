<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Generated\Shared\Transfer\ValidationResponseTransfer;

abstract class AbstractCheckDataStep
{
    /**
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return string[]
     */
    protected function getErrorMessages(ValidationResponseTransfer $validationResponseTransfer): array
    {
        $messages = [];

        foreach ($validationResponseTransfer->getConstraintViolations() as $constraintViolationTransfer) {
            foreach ($constraintViolationTransfer->getMessages() as $constraintViolationMessageTransfer) {
                $messages[] = sprintf(
                    '"%s" property: %s',
                    $constraintViolationTransfer->getPropertyName(),
                    $constraintViolationMessageTransfer->getValue()
                );
            }
        }

        return $messages;
    }
}
