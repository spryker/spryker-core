<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder;

interface ModuleNameCandidatesBuilderConfigInterface
{
    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @return string
     */
    public function getCurrentStoreName(): string;
}
