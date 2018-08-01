<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\ModuleParser;

interface UseStatementParserInterface
{
    /**
     * @param string $module
     *
     * @return array
     */
    public function getUseStatements(string $module): array;
}
