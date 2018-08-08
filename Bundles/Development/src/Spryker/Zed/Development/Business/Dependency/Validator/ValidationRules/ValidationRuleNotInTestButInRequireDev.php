<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules;

use Generated\Shared\Transfer\ModuleDependencyTransfer;
use Generated\Shared\Transfer\ValidationMessageTransfer;

class ValidationRuleNotInTestButInRequireDev implements ValidationRuleInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer
     */
    public function validateModuleDependency(ModuleDependencyTransfer $moduleDependencyTransfer): ModuleDependencyTransfer
    {
        if (!$moduleDependencyTransfer->getIsTestDependency() && $moduleDependencyTransfer->getIsInComposerRequireDev() && !$moduleDependencyTransfer->getIsOptionalDependency()) {
            $moduleDependencyTransfer->setIsValid(false);
            $validationMessageTransfer = new ValidationMessageTransfer();
            $validationMessageTransfer->setMessage('Dependency listed in composer require-dev not found in tests');
            $moduleDependencyTransfer->addValidationMessage($validationMessageTransfer);
        }

        return $moduleDependencyTransfer;
    }
}
