<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\Validator;

use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;

interface MerchantFileValidatorInterface
{
    /**
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
