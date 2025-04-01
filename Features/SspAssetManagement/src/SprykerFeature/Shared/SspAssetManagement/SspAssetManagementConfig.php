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
}
