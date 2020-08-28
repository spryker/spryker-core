<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Dependency\Service;

interface MerchantOmsToUtilDataReaderServiceInterface
{
    /**
     * @return \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface
     */
    public function getCsvReader();
}
