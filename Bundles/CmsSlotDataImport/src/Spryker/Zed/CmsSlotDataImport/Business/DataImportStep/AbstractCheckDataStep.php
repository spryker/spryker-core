<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
