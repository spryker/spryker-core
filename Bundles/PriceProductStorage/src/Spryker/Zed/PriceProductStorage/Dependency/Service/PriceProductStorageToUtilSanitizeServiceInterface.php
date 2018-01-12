<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Dependency\Service;

interface PriceProductStorageToUtilSanitizeServiceInterface
{
    /**
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array);
}
