<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules;

use Generated\Shared\Transfer\ModuleDependencyTransfer;

interface ValidationRuleInterface
{
    /**
     * @var string
     */
    public const ADD_REQUIRE = 'add-require';

    /**
     * @var string
     */
    public const ADD_REQUIRE_DEV = 'add-require-dev';

    /**
     * @var string
     */
    public const ADD_SUGGEST = 'add-suggest';

    /**
     * @var string
     */
    public const REMOVE_REQUIRE = 'remove-require';

    /**
     * @var string
     */
    public const REMOVE_REQUIRE_DEV = 'remove-require-dev';

    /**
     * @var string
     */
    public const REMOVE_SUGGEST = 'remove-suggest';

    /**
     * @var string
     */
    public const MANUAL_FIX = 'manual-fix';

    /**
     * @param \Generated\Shared\Transfer\ModuleDependencyTransfer $moduleDependencyTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer
     */
    public function validateModuleDependency(ModuleDependencyTransfer $moduleDependencyTransfer): ModuleDependencyTransfer;
}
