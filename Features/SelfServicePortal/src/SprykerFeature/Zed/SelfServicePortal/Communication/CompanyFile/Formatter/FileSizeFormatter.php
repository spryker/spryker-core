<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter;

use InvalidArgumentException;

class FileSizeFormatter implements FileSizeFormatterInterface
{
    /**
     * @var list<string>
     */
    protected const LABEL_SIZES = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    /**
     * @var int
     */
    protected const NUMBER_OF_DECIMALS = 2;

    /**
     * @param int $fileSize
     * @param int $numberOfDecimals
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function formatFileSize(int $fileSize, int $numberOfDecimals = self::NUMBER_OF_DECIMALS): string
    {
        if ($fileSize < 0) {
            throw new InvalidArgumentException('File size cannot be negative.');
        }

        if ($fileSize === 0) {
            return '0 B';
        }

        $power = (int)floor(log($fileSize, 1024));
        $calculatedSize = number_format($fileSize / (1024 ** $power), $numberOfDecimals);

        return sprintf('%s %s', $calculatedSize, static::LABEL_SIZES[$power]);
    }
}
