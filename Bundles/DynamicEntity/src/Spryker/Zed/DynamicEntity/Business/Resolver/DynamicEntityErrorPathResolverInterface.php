<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Resolver;

interface DynamicEntityErrorPathResolverInterface
{
    /**
     * @param int $index
     * @param string $tableAlias
     * @param string|null $parentErrorPath
     *
     * @return string
     */
    public function getErrorPath(
        int $index,
        string $tableAlias,
        ?string $parentErrorPath = null
    ): string;
}
