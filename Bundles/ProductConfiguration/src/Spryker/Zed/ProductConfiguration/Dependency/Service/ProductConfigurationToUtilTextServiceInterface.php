<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Dependency\Service;

interface ProductConfigurationToUtilTextServiceInterface
{
    /**
     * @param mixed $value
     * @param string $algorithm
     *
     * @return string
     */
    public function hashValue($value, $algorithm);
}
