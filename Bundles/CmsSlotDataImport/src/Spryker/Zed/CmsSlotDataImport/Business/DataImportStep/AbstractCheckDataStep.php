<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
