<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant;

use Spryker\Shared\DataImportMerchant\DataImportMerchantConfig as SharedDataImportMerchantConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DataImportMerchantConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getInitialStatus(): string
    {
        return SharedDataImportMerchantConfig::STATUS_PENDING;
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getSupportedImporterTypes(): array
    {
        return [];
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getSupportedContentTypes(): array
    {
        return [];
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
        return 'Y-m-d_H-i-s-v';
    }

    /**
     * Specification:
     * - Returns the maximum number of file imports that can be processed at the same time.
     *
     * @api
     *
     * @return int
     */
    public function getMaxFileImportsPerProcessing(): int
    {
        return 10;
    }
}
