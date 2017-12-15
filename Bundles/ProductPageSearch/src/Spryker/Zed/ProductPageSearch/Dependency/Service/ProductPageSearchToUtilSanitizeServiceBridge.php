<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Service;

class ProductPageSearchToUtilSanitizeServiceBridge implements ProductPageSearchToUtilSanitizeServiceInterface
{

    /**
     * @var \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @var \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface $utilSanitizeService
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
    public function arrayFilterRecursive(array $array)
    {
        return $this->utilSanitizeService->arrayFilterRecursive($array);
    }

}
