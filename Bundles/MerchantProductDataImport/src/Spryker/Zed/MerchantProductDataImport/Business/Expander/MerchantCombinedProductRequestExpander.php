<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\Expander;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class MerchantCombinedProductRequestExpander implements MerchantCombinedProductRequestExpanderInterface
{
    /**
     * @param \Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig $merchantProductDataImportConfig
     */
    public function __construct(protected MerchantProductDataImportConfig $merchantProductDataImportConfig)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer
     */
    public function expand(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionRequestTransfer {
        foreach ($dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            if ($dataImportMerchantFileTransfer->getImporterTypeOrFail() === MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT) {
                $dataImportMerchantFileTransfer->getFileInfoOrFail()->setFileSystemName(
                    $this->merchantProductDataImportConfig->getFileSystemName(),
                );
            }
        }

        return $dataImportMerchantFileCollectionRequestTransfer;
    }
}
