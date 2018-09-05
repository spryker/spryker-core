<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules;

use Generated\Shared\Transfer\ModuleDependencyTransfer;

interface ValidationRuleInterface
{
    const ADD_REQUIRE = 'add-require';
    const ADD_REQUIRE_DEV = 'add-require-dev';
    const ADD_SUGGEST = 'add-suggest';

    const REMOVE_REQUIRE = 'remove-require';
    const REMOVE_REQUIRE_DEV = 'remove-require-dev';
    const REMOVE_SUGGEST = 'remove-suggest';

    const MANUAL_FIX = 'manual-fix';

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer
     */
    public function validateModuleDependency(ModuleDependencyTransfer $moduleDependencyTransfer): ModuleDependencyTransfer;
}
