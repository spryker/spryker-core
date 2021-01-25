<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ResolvableCache\CacheReader;

interface CacheReaderInterface
{
    /**
     * @return string[]
     */
    public function read(): array;
}
