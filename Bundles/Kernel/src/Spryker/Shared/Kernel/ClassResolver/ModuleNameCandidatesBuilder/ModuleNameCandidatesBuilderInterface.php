<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder;

interface ModuleNameCandidatesBuilderInterface
{
    /**
     * @param string $moduleName
     *
     * @return string[]
     */
    public function buildModuleNameCandidates(string $moduleName): array;
}
