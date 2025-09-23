<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\Expander;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;

interface MerchantCombinedProductRequestExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer
     */
    public function expand(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionRequestTransfer;
}
