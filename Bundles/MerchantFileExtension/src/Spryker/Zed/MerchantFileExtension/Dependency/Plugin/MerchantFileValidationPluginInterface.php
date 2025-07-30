<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFileExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;

interface MerchantFileValidationPluginInterface
{
    /**
     * Specification:
     * - Validates the merchant file transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     * @param \Generated\Shared\Transfer\MerchantFileResultTransfer $merchantFileResultTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileResultTransfer
     */
    public function validate(
        MerchantFileTransfer $merchantFileTransfer,
        MerchantFileResultTransfer $merchantFileResultTransfer
    ): MerchantFileResultTransfer;
}
