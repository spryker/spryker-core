<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Dependency\Service;

interface CartToUtilTextServiceInterface
{
    /**
     * @param string $value
     * @param string $algorithm
     *
     * @return string
     */
    public function hashValue(string $value, string $algorithm): string;
}
