<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules;

use Generated\Shared\Transfer\ModuleDependencyTransfer;

interface ValidationRuleInterface
{
    public const ADD_REQUIRE = 'add-require';
    public const ADD_REQUIRE_DEV = 'add-require-dev';
    public const ADD_SUGGEST = 'add-suggest';

    public const REMOVE_REQUIRE = 'remove-require';
    public const REMOVE_REQUIRE_DEV = 'remove-require-dev';
    public const REMOVE_SUGGEST = 'remove-suggest';

    public const MANUAL_FIX = 'manual-fix';

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer
     */
    public function validateModuleDependency(ModuleDependencyTransfer $moduleDependencyTransfer): ModuleDependencyTransfer;
}
