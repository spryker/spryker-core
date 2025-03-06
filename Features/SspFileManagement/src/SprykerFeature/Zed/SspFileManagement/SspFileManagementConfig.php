<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConstants;

/**
 * @method \SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig getSharedConfig()
 */
class SspFileManagementConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const DEFAULT_MAX_FILE_SIZE = '100M';

    /**
     * @var array<string>
     */
    protected const DEFAULT_ALLOWED_MIME_TYPES = [
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/heic',
        'image/heif',
    ];

    /**
     * @var array<string>
     */
    protected const DEFAULT_ALLOWED_FILE_EXTENSIONS = [
        '.pdf',
        '.jpeg',
        '.jpg',
        '.png',
        '.heic',
        '.heif',
    ];

    /**
     * @var string
     */
    protected const FILE_REFERENCE_PREFIX = 'FILE-';

    /**
     * @var string
     */
    protected const FILE_REFERENCE_NAME = 'FileReference';

    /**
     * @var int
     */
    protected const DEFAULT_AUTOCOMPLETE_LIMIT = 10;

    /**
     * @api
     *
     * @return string
     */
    public function getMaxFileSize(): string
    {
        return static::DEFAULT_MAX_FILE_SIZE;
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getAllowedMimeTypes(): array
    {
        return static::DEFAULT_ALLOWED_MIME_TYPES;
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getAllowedFileExtensions(): array
    {
        return static::DEFAULT_ALLOWED_FILE_EXTENSIONS;
    }

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
     * @api
     *
     * @return string
     */
    public function getFileSequenceNumberPrefix(): string
    {
        return static::FILE_REFERENCE_PREFIX;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFileSequenceNumberName(): string
    {
        return static::FILE_REFERENCE_NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDateTimeZone(): string
    {
        return $this->getSharedConfig()->getDateTimeZone();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStorageName(): string
    {
        return $this->get(SspFileManagementConstants::STORAGE_NAME);
    }

    /**
     * Specification:
     * - Returns autocomplete limit.
     *
     * @api
     *
     * @return int
     */
    public function getAutocompleteLimit(): int
    {
        return static::DEFAULT_AUTOCOMPLETE_LIMIT;
    }
}
