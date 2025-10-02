<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Validator;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;

interface MerchantCombinedProductOfferValidatorInterface
{
    public function validateDataImportMerchantFileCollection(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer;
}
