<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImportMerchant;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileRequestExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportBusinessFactory getBusinessFactory()
 */
class MerchantCombinedMerchantProductOfferFileRequestExpanderPlugin extends AbstractPlugin implements DataImportMerchantFileRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `DataImportMerchantFileTransfer.importerType` to be set.
     * - Requires `DataImportMerchantFileTransfer.fileInfo` to be set.
     * - Expands data import merchant file collection request.
     * - Iterates over `DataImportMerchantFileCollectionRequestTransfer.dataImportMerchantFiles`.
     * - Sets `DataImportMerchantFileTransfer.fileInfo.fileSystemName` for files with type `merchant-combined-product-offer`.
     *
     * @api
     */
    public function expand(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionRequestTransfer {
        return $this->getBusinessFactory()
            ->createMerchantCombinedMerchantProductOfferRequestExpander()
            ->expandDataImportMerchantFileCollectionRequestWithFileSystemName($dataImportMerchantFileCollectionRequestTransfer);
    }
}
