<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Dependency\Service;

class SearchElasticsearchToUtilSanitizeServiceBridge implements SearchElasticsearchToUtilSanitizeServiceInterface
{
    /**
     * @var \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct($utilSanitizeService)
    {
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function filterOutBlankValuesRecursively(array $array): array
    {
        return $this->utilSanitizeService->filterOutBlankValuesRecursively($array);
    }
}
