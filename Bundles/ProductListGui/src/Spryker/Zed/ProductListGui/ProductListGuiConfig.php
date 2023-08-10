<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductListGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const REDIRECT_URL_DEFAULT = '/product-list-gui';

    /**
     * @var array<string, list<string>>
     */
    protected const FILE_ALLOWED_EXTENSIONS_WITH_MIME_TYPES = [
        'csv' => [
            'text/csv',
            'text/plain',
        ],
    ];

    /**
     * @var bool
     */
    protected const IS_FILE_EXTENSION_VALIDATION_ENABLED = false;

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultRedirectUrl(): string
    {
        return static::REDIRECT_URL_DEFAULT;
    }

    /**
     * Specification:
     * - Specifies allowed extensions with their MIME types for the uploaded file.
     *
     * @api
     *
     * @return array<string, list<string>>
     */
    public function getFileAllowedExtensionsWithMimeTypes(): array
    {
        return static::FILE_ALLOWED_EXTENSIONS_WITH_MIME_TYPES;
    }

    /**
     * Specification:
     * - Defines whether the extension validation for the uploaded file is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isFileExtensionValidationEnabled(): bool
    {
        return static::IS_FILE_EXTENSION_VALIDATION_ENABLED;
    }
}
