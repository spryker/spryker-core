<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Communication\Plugin\MerchantFile;

use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFileValidationPluginInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantProductDataImport\Business\MerchantProductDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductDataImport\Business\MerchantProductDataImportBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig getConfig()
 */
class MerchantCombinedProductHeadersMerchantFileValidationPlugin extends AbstractPlugin implements MerchantFileValidationPluginInterface
{
    /**
     * @uses \Spryker\Shared\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig::FILE_TYPE_DATA_IMPORT
     *
     * @var string
     */
    protected const MERCHANT_FILE_TYPE_DATA_IMPORT = 'data-import';

    /**
     * @api
     *
     * @inheritDoc
     */
    public function validate(
        MerchantFileTransfer $merchantFileTransfer,
        MerchantFileResultTransfer $merchantFileResultTransfer
    ): MerchantFileResultTransfer {
        if (
            $merchantFileTransfer->getType() !== static::MERCHANT_FILE_TYPE_DATA_IMPORT
            || $merchantFileTransfer->getMerchantFileImport()?->getEntityType() !== MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT
        ) {
            return $merchantFileResultTransfer;
        }

        return $this->getBusinessFactory()
            ->createProductHeadersValidator()
            ->validate($merchantFileTransfer, $merchantFileResultTransfer);
    }
}
