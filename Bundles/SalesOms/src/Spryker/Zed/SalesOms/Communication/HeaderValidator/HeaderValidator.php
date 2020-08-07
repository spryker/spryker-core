<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Communication\HeaderValidator;

use Generated\Shared\Transfer\HeaderValidatorResponseTransfer;
use Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface;

class HeaderValidator implements HeaderValidatorInterface
{
    /**
     * @param string[] $columns
     * @param \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface $csvReader
     *
     * @return \Generated\Shared\Transfer\HeaderValidatorResponseTransfer
     */
    public function validate(array $columns, CsvReaderInterface $csvReader): HeaderValidatorResponseTransfer
    {
        $headerValidatorResponseTransfer = new HeaderValidatorResponseTransfer();
        $headerValidatorResponseTransfer->setIsSuccessful(true);

        if (array_diff($columns, $csvReader->getColumns())) {
            $headerValidatorResponseTransfer->setIsSuccessful(false);
        }

        return $headerValidatorResponseTransfer;
    }
}
