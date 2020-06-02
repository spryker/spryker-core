<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\HeaderValidator;

use Generated\Shared\Transfer\HeaderValidatorResponseTransfer;
use Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface;

interface HeaderValidatorInterface
{
    /**
     * @param string[] $columns
     * @param \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface $csvReader
     *
     * @return \Generated\Shared\Transfer\HeaderValidatorResponseTransfer
     */
    public function validate(array $columns, CsvReaderInterface $csvReader): HeaderValidatorResponseTransfer;
}
