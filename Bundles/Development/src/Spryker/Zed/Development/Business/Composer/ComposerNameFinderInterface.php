<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

interface ComposerNameFinderInterface
{
    /**
     * @param string $moduleName
     *
     * @return string|null
     */
    public function findComposerNameByModuleName(string $moduleName): ?string;
}
