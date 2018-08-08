<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules;

use Generated\Shared\Transfer\ModuleDependencyTransfer;
use Generated\Shared\Transfer\ValidationMessageTransfer;

class ValidationRuleIsOptionalButInRequire implements ValidationRuleInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer
     */
    public function validateModuleDependency(ModuleDependencyTransfer $moduleDependencyTransfer): ModuleDependencyTransfer
    {
        if ($moduleDependencyTransfer->getIsOptionalDependency() && $moduleDependencyTransfer->getIsInComposerRequire() && !$moduleDependencyTransfer->getIsOwnExtensionModule()) {
            $moduleDependencyTransfer->setIsValid(false);
            $validationMessageTransfer = new ValidationMessageTransfer();
            $validationMessageTransfer->setMessage('Optional dependency should not be listed in composer require');
            $moduleDependencyTransfer->addValidationMessage($validationMessageTransfer);
        }

        return $moduleDependencyTransfer;
    }
}
