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
     * @var array<string>
     */
    protected const SSP_ASSET_STATUSES = [
        'pending',
        'in_review',
        'approved',
        'deactivated',
    ];

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
        return static::SSP_ASSET_STATUSES;
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
     * @return string
     */
    protected function createPrefix(): string
    {
        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = static::SSP_ASSET_REFERENCE_PREFIX;

        return sprintf('%s--', implode('-', $sequenceNumberPrefixParts));
    }
}
