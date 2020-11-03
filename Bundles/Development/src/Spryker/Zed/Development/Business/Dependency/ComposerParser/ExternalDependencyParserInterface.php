<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\ComposerParser;

interface ExternalDependencyParserInterface
{
    /**
     * @param string $className
     *
     * @return string|null
     */
    public function findPackageNameByNamespace(string $className): ?string;
}
