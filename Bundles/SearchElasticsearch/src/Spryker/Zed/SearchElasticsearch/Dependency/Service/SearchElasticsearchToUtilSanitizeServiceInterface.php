<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Dependency\Service;

interface SearchElasticsearchToUtilSanitizeServiceInterface
{
    /**
     * @param array $array
     *
     * @return array
     */
    public function filterOutBlankValuesRecursively(array $array): array;
}
