<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder;

interface ClassNameCandidatesBuilderInterface
{
    /**
     * @param string $module
     * @param string $classNamePattern
     *
     * @return string[]
     */
    public function buildClassNames(string $module, string $classNamePattern): array;
}
