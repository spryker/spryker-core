<?php
/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Generated\Shared\Transfer\DataObjectValidationResponseTransfer;

abstract class AbstractCheckDataStep
{
    /**
     * @param \Generated\Shared\Transfer\DataObjectValidationResponseTransfer $dataObjectValidationResponseTransfer
     *
     * @return string[]
     */
    protected function getErrorMessages(DataObjectValidationResponseTransfer $dataObjectValidationResponseTransfer): array
    {
        $messages = [];

        foreach ($dataObjectValidationResponseTransfer->getValidationResults() as $validationResult) {
            foreach ($validationResult->getMessages() as $propertyValidationResultMessage) {
                $messages[] = sprintf(
                    '"%s" property: %s',
                    $validationResult->getPropertyName(),
                    $propertyValidationResultMessage->getValue()
                );
            }
        }

        return $messages;
    }
}