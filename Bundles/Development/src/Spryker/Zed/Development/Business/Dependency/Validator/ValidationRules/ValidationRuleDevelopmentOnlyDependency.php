<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules;

use Generated\Shared\Transfer\ModuleDependencyTransfer;
use Generated\Shared\Transfer\ValidationMessageTransfer;

class ValidationRuleDevelopmentOnlyDependency implements ValidationRuleInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer
     */
    public function validateModuleDependency(ModuleDependencyTransfer $moduleDependencyTransfer): ModuleDependencyTransfer
    {
        if ($this->isDevelopmentOnlyDependency($moduleDependencyTransfer) && $moduleDependencyTransfer->getIsInComposerRequire() && !$moduleDependencyTransfer->getIsOwnExtensionModule()) {
            $moduleDependencyTransfer->setIsValid(false);
            $validationMessageTransfer = new ValidationMessageTransfer();
            $validationMessageTransfer->setMessage('Dependency is marked as dev only but listed in composer require');
            $validationMessageTransfer->setFixType(static::REMOVE_REQUIRE);

            $moduleDependencyTransfer->addValidationMessage($validationMessageTransfer);
        }

        return $moduleDependencyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return bool
     */
    protected function isDevelopmentOnlyDependency(ModuleDependencyTransfer $moduleDependencyTransfer): bool
    {
        $dependencyTypes = $moduleDependencyTransfer->getDependencyTypes();
        if (count($dependencyTypes) === 1 && current($dependencyTypes) === 'dev only') {
            return true;
        }

        return false;
    }
}
