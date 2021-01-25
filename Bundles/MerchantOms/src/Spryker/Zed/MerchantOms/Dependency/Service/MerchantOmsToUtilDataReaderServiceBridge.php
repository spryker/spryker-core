<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Dependency\Service;

class MerchantOmsToUtilDataReaderServiceBridge implements MerchantOmsToUtilDataReaderServiceInterface
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
