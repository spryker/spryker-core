<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Dependency\Service;

interface CmsBlockProductStorageToUtilSanitizeServiceInterface
{
    /**
     * @deprecated Use filterOutEmptyValuesRecursively() instead.
     *
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array);

    /**
     * @param array $array
     *
     * @return array
     */
    public function filterOutEmptyValuesRecursively(array $array): array;
}
