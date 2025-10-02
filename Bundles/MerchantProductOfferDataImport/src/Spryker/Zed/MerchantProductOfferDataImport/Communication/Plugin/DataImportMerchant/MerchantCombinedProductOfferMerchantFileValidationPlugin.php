<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImportMerchant;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig getConfig()
 */
class MerchantCombinedProductOfferMerchantFileValidationPlugin extends AbstractPlugin implements DataImportMerchantFileValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates required headers in merchant combined product offer data import files.
     * - Checks for presence of 'offer_reference' and 'concrete_sku' fields.
     * - Returns validation errors for missing required headers.
     *
     * @api
     */
    public function validate(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        return $this->getBusinessFactory()
            ->createMerchantCombinedProductOfferValidator()
            ->validateDataImportMerchantFileCollection($dataImportMerchantFileCollectionResponseTransfer);
    }
}
