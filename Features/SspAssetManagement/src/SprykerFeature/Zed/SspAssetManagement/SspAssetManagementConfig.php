<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \SprykerFeature\Shared\SspAssetManagement\SspAssetManagementConfig getSharedConfig()
 */
class SspAssetManagementConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const NAME_SSP_ASSET_REFERENCE = 'SspAssetReference';

    /**
     * @var string
     */
    protected const SSP_ASSET_REFERENCE_PREFIX = 'AST';

    /**
     * @var string
     */
    protected const INITIAL_SSP_ASSET_STATUS = 'pending';

    /**
     * Specification:
     * - Returns the settings for the ssp asset sequence number.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getAssetSequenceNumberSettings(): SequenceNumberSettingsTransfer
    {
        return (new SequenceNumberSettingsTransfer())
            ->setName(static::NAME_SSP_ASSET_REFERENCE)
            ->setPrefix($this->createPrefix());
    }

    /**
     * Specification:
     * - Returns the asset statuses.
     * - Used for managing the asset status in the ssp asset workflow.
     * - Managed by Backoffice user and visible for company user.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAssetStatuses(): array
    {
        return $this->getSharedConfig()->getAssetStatuses();
    }

    /**
     * Specification:
     * - Returns the initial asset status.
     *
     * @api
     *
     * @return string
     */
    public function getInitialAssetStatus(): string
    {
        return static::INITIAL_SSP_ASSET_STATUS;
    }

    /**
     * Specification:
     * - Defines the Storage for asset image file.
     * - A `FileSystemConstants::FILESYSTEM_SERVICE` with the same storage name must be defined.
     *
     * @api
     *
     * @return string|null
     */
    public function getStorageName(): ?string
    {
        return null;
    }

    /**
     * Specification:
     * - Returns the allowed file extensions for file uploads during ssp asset creation.
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
     * - Returns the allowed file mime types for file uploads during ssp asset creation.
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
     * - Returns the default file max size for file uploads for ssp asset.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultFileMaxSize(): string
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
        return $this->getSharedConfig()->getDownloadChunkSize();
    }

    /**
     * @return string
     */
    protected function createPrefix(): string
    {
        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = static::SSP_ASSET_REFERENCE_PREFIX;

        return sprintf('%s--', implode('-', $sequenceNumberPrefixParts));
    }

    /**
     * Specification:
     * - Returns the asset statuses that are allowed for a specific action.
     *
     * @api
     *
     * @param string $allowedAction
     *
     * @return array<string>
     */
    public function getStatusesByAllowedAction(string $allowedAction): array
    {
        return $this->getSharedConfig()->getStatusesByAllowedAction($allowedAction);
    }
}
