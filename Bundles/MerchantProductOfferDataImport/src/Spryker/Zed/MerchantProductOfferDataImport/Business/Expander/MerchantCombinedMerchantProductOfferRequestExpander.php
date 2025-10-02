<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Expander;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig;

class MerchantCombinedMerchantProductOfferRequestExpander implements MerchantCombinedMerchantProductOfferRequestExpanderInterface
{
    public function __construct(protected MerchantProductOfferDataImportConfig $merchantProductOfferDataImportConfig)
    {
    }

    public function expandDataImportMerchantFileCollectionRequestWithFileSystemName(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionRequestTransfer {
        foreach ($dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            if ($dataImportMerchantFileTransfer->getImporterTypeOrFail() === $this->merchantProductOfferDataImportConfig->getImportTypeMerchantCombinedProductOffer()) {
                $dataImportMerchantFileTransfer->getFileInfoOrFail()->setFileSystemName(
                    $this->merchantProductOfferDataImportConfig->getFileSystemName(),
                );
            }
        }

        return $dataImportMerchantFileCollectionRequestTransfer;
    }
}
