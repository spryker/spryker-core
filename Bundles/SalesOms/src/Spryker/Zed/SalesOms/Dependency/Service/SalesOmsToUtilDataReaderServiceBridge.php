<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Dependency\Service;

class SalesOmsToUtilDataReaderServiceBridge implements SalesOmsToUtilDataReaderServiceInterface
{
    /**
     * @var \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected $utilDataReaderService;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     */
    public function __construct($utilDataReaderService)
    {
        $this->utilDataReaderService = $utilDataReaderService;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface
     */
    public function getCsvReader()
    {
        return $this->utilDataReaderService->getCsvReader();
    }
}
