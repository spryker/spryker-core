<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ClassNameFinder;

interface ClassNameFinderInterface
{
    /**
     * @param string $moduleName
     * @param string $classNamePattern
     * @param bool $throwException
     *
     * @return string|null
     */
    public function findClassName(string $moduleName, string $classNamePattern, bool $throwException = true): ?string;
}
