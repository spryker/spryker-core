<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\SspAssetManagement\SspAssetManagementConstants;

/**
 * @method \SprykerFeature\Shared\SspAssetManagement\SspAssetManagementConfig getSharedConfig()
 */
class SspAssetManagementConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const DOWNLOAD_CHUNK_SIZE = 1048576; // 1024 * 1024;

    /**
     * @var string
     */
    protected const PARAM_PAGE = 'page';

    /**
     * @var string
     */
    protected const PARAM_PER_PAGE = 'perPage';

    /**
     * Specification:
     * - Returns allowed file extensions for file upload for ssp asset.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAllowedFileExtensions(): array
    {
        return $this->getSharedConfig()->getAllowedFileExtensions();
    }

    /**
     * Specification:
     * - Returns allowed file mime types for file upload for ssp asset.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAllowedFileMimeTypes(): array
    {
        return $this->getSharedConfig()->getAllowedFileMimeTypes();
    }

    /**
     * Specification:
     * - Returns the default file max size per file upload during ssp asset creation.
     * - File size can be given with units: Kb Mb or Gb.
     *
     * @api
     *
     * @return string
     */
    public function getFileMaxSize(): string
    {
        return $this->getSharedConfig()->getDefaultFileMaxSize();
    }

    /**
     * Specification:
     * - Defines the download chunk size in bytes.
     *
     * @api
     *
     * @return int
     */
    public function getDownloadChunkSize(): int
    {
        return static::DOWNLOAD_CHUNK_SIZE;
    }

    /**
     * Specification:
     * - Returns base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080).
     *
     * @api
     *
     * @return string
     */
    public function getYvesBaseUrl(): string
    {
        return $this->get(SspAssetManagementConstants::BASE_URL_YVES);
    }

    /**
     * Specification:
     * - Returns parameter name for ssp asset page number.
     *
     * @api
     *
     * @return string
     */
    public function getParamPage(): string
    {
        return static::PARAM_PAGE;
    }

    /**
     * Specification:
     * - Returns parameter name for ssp assets per page.
     *
     * @api
     *
     * @return string
     */
    public function getParamPerPage(): string
    {
        return static::PARAM_PER_PAGE;
    }

    /**
     * Specification:
     * - Returns page size for ssp assets list page.
     *
     * @api
     *
     * @return int
     */
    public function getSspAssetCountPerPageList(): int
    {
        return 10;
    }
}
