<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductDataImport\Business\MerchantProductDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductDataImport\Business\MerchantProductDataImportBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig getConfig()
 */
class MerchantCombinedProductMerchantFileValidationPlugin extends AbstractPlugin implements DataImportMerchantFileValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates required headers in merchant combined product data import files.
     * - Requires `DataImportMerchantFile.fileInfo.content` to be set.
     * - Reads CSV headers from `DataImportMerchantFile.fileInfo.content`.
     * - Checks for presence of 'abstract_sku' and 'product.assigned_product_type' fields.
     * - Returns validation errors for missing required headers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function validate(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        return $this->getBusinessFactory()
            ->createMerchantCombinedProductValidator()
            ->validateDataImportMerchantFileCollection($dataImportMerchantFileCollectionResponseTransfer);
    }
}
