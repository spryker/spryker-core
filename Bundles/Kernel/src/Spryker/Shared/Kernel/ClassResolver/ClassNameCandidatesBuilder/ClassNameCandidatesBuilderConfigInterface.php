<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder;

interface ClassNameCandidatesBuilderConfigInterface
{
    /**
     * @return array<string>
     */
    public function getProjectOrganizations(): array;

    /**
     * @return array<string>
     */
    public function getCoreOrganizations(): array;
}
