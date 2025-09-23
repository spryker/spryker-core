<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\PossibleCsvHeaderExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductDataImport\Business\MerchantProductDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductDataImport\Business\MerchantProductDataImportBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig getConfig()
 */
class MerchantCombinedProductPossibleCsvHeaderExpanderPlugin extends AbstractPlugin implements PossibleCsvHeaderExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands possible CSV headers for merchant combined product data import files.
     *
     * @api
     *
     * @param array<string, list<string>> $possibleCsvHeaders
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, list<string>>
     */
    public function expand(array $possibleCsvHeaders, MerchantTransfer $merchantTransfer): array
    {
        return $this->getBusinessFactory()
            ->createPossibleCsvHeaderExpander()
            ->expand($possibleCsvHeaders, $merchantTransfer);
    }
}
