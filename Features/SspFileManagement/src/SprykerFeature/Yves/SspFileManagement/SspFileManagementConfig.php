<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement;

use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig getSharedConfig()
 */
class SspFileManagementConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const PARAM_PAGE = 'page';

    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;

    /**
     * @var int
     */
    protected const DEFAULT_MAX_PER_PAGE = 20;

    /**
     * @var list<string>
     */
    protected const ALLOWED_FILE_TYPES = [
        'pdf',
        'jpeg',
        'jpg',
        'png',
        'heic',
        'heif',
    ];

    /**
     * @var int
     */
    protected const DOWNLOAD_CHUNK_SIZE = 1024 * 1024;

    /**
     * @api
     *
     * @return list<string>
     */
    public function getEntityTypes(): array
    {
        return $this->getSharedConfig()->getEntityTypes();
    }

    /**
     * Specification:
     * - Returns date time zone.
     * - Used for filtering files by date.
     *
     * @api
     *
     * @return string
     */
    public function getDateTimeZone(): string
    {
        return $this->getSharedConfig()->getDateTimeZone();
    }

    /**
     * Specification:
     * - Returns allowed file types.
     *
     * @api
     *
     * @return list<string>
     */
    public function getAllowedFileTypes(): array
    {
        return static::ALLOWED_FILE_TYPES;
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
     * - Returns default page number for pagination.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultPage(): int
    {
        return static::DEFAULT_PAGE;
    }

    /**
     * Specification:
     * - Returns parameter name for page number.
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
     * - Returns maximum items per page for pagination.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultMaxPerPage(): int
    {
        return static::DEFAULT_MAX_PER_PAGE;
    }
}
