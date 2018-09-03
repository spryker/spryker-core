<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules;

use Generated\Shared\Transfer\ModuleDependencyTransfer;

class ValidationRuleComposite implements ValidationRuleInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface[]
     */
    protected $validationRules;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface[] $validationRules
     */
    public function __construct(array $validationRules)
    {
        $this->validationRules = $validationRules;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer
     */
    public function validateModuleDependency(ModuleDependencyTransfer $moduleDependencyTransfer): ModuleDependencyTransfer
    {
        foreach ($this->validationRules as $validationRule) {
            $moduleDependencyTransfer = $validationRule->validateModuleDependency($moduleDependencyTransfer);
        }

        return $moduleDependencyTransfer;
    }
}
