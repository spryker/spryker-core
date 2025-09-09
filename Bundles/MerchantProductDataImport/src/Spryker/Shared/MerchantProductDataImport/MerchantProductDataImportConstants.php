<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Shared\MerchantProductDataImport;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface MerchantProductDataImportConstants
{
    /**
     * Specification:
     * - Defines the filesystem name for storing data import merchant uploaded files.
     * - Used as a fallback if no filesystem name is specified.
     *
     * @api
     *
     * @var string
     */
    public const FILE_SYSTEM_NAME = 'MERCHANT_PRODUCT_DATA_IMPORT:FILE_SYSTEM_NAME';
}
