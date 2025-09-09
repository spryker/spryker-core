<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DataImportMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const MAX_FILE_SIZE_MB = 10;

    /**
     * Specification:
     * - Returns the maximum file size for file uploads in bytes.
     *
     * @api
     *
     * @return int
     */
    public function getMaxFileSizeInBytes(): int
    {
        return static::MAX_FILE_SIZE_MB * 1024 * 1024;
    }

    /**
     * Specification:
     *  - Returns the batch size for data import merchant file collection reading.
     *
     * @api
     *
     * @return int
     */
    public function getReadDataImportMerchantFileCollectionBatchSize(): int
    {
        return 100;
    }

    /**
     * Specification:
     * - Returns a list of supported data importer types.
     *
     * @api
     *
     * @return list<string>
     */
    public function getSupportedImporterTypes(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns a list of data import template file asset paths.
     * - Key is a label for the template, value is an asset path.
     * - Example: ['CSV template Product' => 'files/product.csv']
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getDataImportTemplates(): array
    {
        return [];
    }
}
