<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui;

use Spryker\Shared\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig as SharedFileImportMerchantPortalGuiConfig;
use Spryker\Shared\MerchantFile\MerchantFileConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class FileImportMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const MAX_FILE_SIZE = 10485760; // 10 MB

    /**
     * @var int
     */
    protected const MAX_FILE_IMPORTS_PER_PROCESSING = 100;

    /**
     * Specification:
     * - Returns the maximum file size for file uploads in bytes.
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
     * @api
     *
     * @return array<string>
     */
    public function getImportTypes(): array
    {
        return [];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFileSystemName(): string
    {
        return $this->get(MerchantFileConstants::FILE_SYSTEM_NAME);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getInitialFileImportStatus(): string
    {
        return SharedFileImportMerchantPortalGuiConfig::STATUS_PENDING;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMaxFileImportsPerProcessing(): int
    {
        return static::MAX_FILE_IMPORTS_PER_PROCESSING;
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
