<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Resolver;

interface PathResolverInterface
{
    /**
     * @param string|null $module
     * @param string|null $namespace
     * @param string|null $path
     * @param array $options
     *
     * @return array
     */
    public function resolvePaths(?string $module, ?string $namespace, ?string $path, array $options): array;
}
