<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileRequestExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductDataImport\Business\MerchantProductDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductDataImport\Business\MerchantProductDataImportBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig getConfig()
 */
class MerchantCombinedProductMerchantFileRequestExpanderPlugin extends AbstractPlugin implements DataImportMerchantFileRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `DataImportMerchantFileTransfer.importerType` to be set.
     * - Requires `DataImportMerchantFileTransfer.fileInfo` to be set.
     * - Expands data import merchant file collection request.
     * - Iterates over `DataImportMerchantFileCollectionRequestTransfer.dataImportMerchantFiles`.
     * - Sets `DataImportMerchantFileTransfer.fileInfo.fileSystemName` for files with type `merchant-combined-product`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer
     */
    public function expand(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionRequestTransfer {
        return $this->getBusinessFactory()
            ->createMerchantCombinedProductRequestExpander()
            ->expand($dataImportMerchantFileCollectionRequestTransfer);
    }
}
