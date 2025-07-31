<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Shared\MerchantFile;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface MerchantFileConstants
{
    /**
     * Specification:
     * - Defines the filesystem name for storing merchant uploaded files.
     * - Used as a fallback if no filesystem name is specified.
     *
     * @api
     *
     * @var string
     */
    public const FILE_SYSTEM_NAME = 'MERCHANT_FILE:FILE_SYSTEM_NAME';
}
