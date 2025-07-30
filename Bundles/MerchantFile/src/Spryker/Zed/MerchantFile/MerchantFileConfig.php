<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile;

use Spryker\Shared\MerchantFile\MerchantFileConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantFileConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var int
     */
    protected const MAX_FILE_SIZE = 10_485_760; // 10 MB

    /**
     * @api
     *
     * @var string
     */
    protected const FILE_NAME_SUFFIX_DATE_TIME_FORMAT = 'Y-m-d_H-i-s-v';

    /**
     * Specification:
     * - Returns a mapping between file types and their supported content types (MIME type).
     * - Structure example:
     * [
     *   'data-import' => ['text/csv', 'application/csv', 'text/plain'],
     *   'FILE_TYPE_1' => ['MIME_TYPE_1', 'MIME_TYPE_2'],
     *   ...
     *   'FILE_TYPE_N' => ['MIME_TYPE_1', ..., 'MIME_TYPE_N'],
     * ]
     *
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getFileTypeToContentTypeMapping(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns the maximum file size allowed for merchant file uploads.
     * - The value specified in bytes.
     * - Default is 10_485_760 bytes (10 MB)
     *
     * @api
     *
     * @return int
     */
    public function getMaxFileSize(): int
    {
        return static::MAX_FILE_SIZE;
    }

    /**
     * Specification:
     * - Returns the filesystem name used for storing merchant uploaded files.
     *
     * @api
     *
     * @return string
     */
    public function getFileSystemName(): string
    {
        return $this->get(MerchantFileConstants::FILE_SYSTEM_NAME);
    }

    /**
     * Specification:
     * - Returns the date time format used for generating unique file name suffixes.
     * - Uses PHP datetime format {@link https://www.php.net/manual/en/datetime.format.php}.
     *
     * @api
     *
     * @return string
     */
    public function getFileSuffixDateTimeFormat(): string
    {
        return static::FILE_NAME_SUFFIX_DATE_TIME_FORMAT;
    }
}
