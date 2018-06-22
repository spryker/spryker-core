<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Dependency\Service;

use SplFileObject;

class ProductListGuiToUtilCsvServiceBridge implements ProductListGuiToUtilCsvServiceInterface
{
    /**
     * @var \Spryker\Service\UtilCsv\UtilCsvServiceInterface
     */
    protected $utilCsvService;

    /**
     * @param \Spryker\Service\UtilCsv\UtilCsvServiceInterface $utilCsvService
     */
    public function __construct($utilCsvService)
    {
        $this->utilCsvService = $utilCsvService;
    }

    /**
     * @param \SplFileObject $file
     *
     * @return array
     */
    public function readFile(SplFileObject $file): array
    {
        return $this->utilCsvService->readFile($file);
    }
}
