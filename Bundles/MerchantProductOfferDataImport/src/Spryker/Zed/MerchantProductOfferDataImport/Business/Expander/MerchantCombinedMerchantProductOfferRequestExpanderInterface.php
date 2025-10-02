<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Expander;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;

interface MerchantCombinedMerchantProductOfferRequestExpanderInterface
{
    public function expandDataImportMerchantFileCollectionRequestWithFileSystemName(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionRequestTransfer;
}
