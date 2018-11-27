<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\ModuleParser;

use Symfony\Component\Finder\SplFileInfo;

interface UseStatementParserInterface
{
    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return array
     */
    public function getUseStatements(SplFileInfo $fileInfo): array;
}
