<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SspAssetManagement;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SspAssetManagementConfig extends AbstractSharedConfig
{
    /**
     * @var int
     */
    protected const DOWNLOAD_CHUNK_SIZE = 1048576; // 1024 * 1024;

    /**
     * @var string
     */
    public const STATUS_PENDING = 'pending';

    /**
     * @var string
     */
    public const STATUS_IN_REVIEW = 'in_review';

    /**
     * @var string
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    public const STATUS_DEACTIVATED = 'deactivated';

    /**
     * @var string
     */
    public const ACTION_UNASSIGN = 'unassign';

    /**
     * @var string
     */
    public const ACTION_UPDATE = 'update';

    /**
     * @var string
     */
    public const ACTION_VIEW = 'view';

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
        return ['jpg', 'jpeg', 'png'];
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
        return [
            'image/png',
            'image/jpeg',
            'image/jpg',
        ];
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
        return '10M';
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
        return [
            static::STATUS_PENDING => 'Pending',
            static::STATUS_IN_REVIEW => 'In Review',
            static::STATUS_APPROVED => 'Approved',
            static::STATUS_DEACTIVATED => 'Deactivated',
        ];
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
        $statuses = [];

        foreach ($this->getSspStatusAllowedActionsMapping() as $status => $allowedActions) {
            if (in_array($allowedAction, $allowedActions)) {
                $statuses[] = $status;
            }
        }

        return $statuses;
    }

    /**
     * @api
     *
     * @return array<string, array<string>>
     */
    public function getSspStatusAllowedActionsMapping(): array
    {
        return [
            static::STATUS_PENDING => [
                static::ACTION_UNASSIGN,
                static::ACTION_UPDATE,
                static::ACTION_VIEW,
            ],
            static::STATUS_IN_REVIEW => [
                static::ACTION_VIEW,
            ],
            static::STATUS_APPROVED => [
                static::ACTION_UNASSIGN,
                static::ACTION_UPDATE,
                static::ACTION_VIEW,
            ],
            static::STATUS_DEACTIVATED => [],
        ];
    }
}
