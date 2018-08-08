<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules;

use Generated\Shared\Transfer\ModuleDependencyTransfer;
use Generated\Shared\Transfer\ValidationMessageTransfer;

class ValidationRuleInRequireAndRequireDev implements ValidationRuleInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer
     */
    public function validateModuleDependency(ModuleDependencyTransfer $moduleDependencyTransfer): ModuleDependencyTransfer
    {
        if ($moduleDependencyTransfer->getIsInComposerRequire() && $moduleDependencyTransfer->getIsInComposerRequireDev()) {
            $moduleDependencyTransfer->setIsValid(false);
            $validationMessageTransfer = new ValidationMessageTransfer();
            $validationMessageTransfer->setMessage('Dependency is listed in require and require-dev, only one is needed');
            $moduleDependencyTransfer->addValidationMessage($validationMessageTransfer);
        }

        return $moduleDependencyTransfer;
    }
}
