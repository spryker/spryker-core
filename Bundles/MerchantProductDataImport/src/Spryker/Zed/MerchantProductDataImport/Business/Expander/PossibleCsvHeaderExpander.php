<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\Expander;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToCurrencyFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToLocaleFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToMerchantStockFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToProductAttributeFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToStoreFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class PossibleCsvHeaderExpander implements PossibleCsvHeaderExpanderInterface
{
    /**
     * @param \Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig $merchantProductDataImportConfig
     * @param \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToMerchantStockFacadeInterface $merchantStockFacade
     * @param \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\MerchantProductDataImport\Dependency\Facade\MerchantProductDataImportToProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct(
        protected MerchantProductDataImportConfig $merchantProductDataImportConfig,
        protected MerchantProductDataImportToLocaleFacadeInterface $localeFacade,
        protected MerchantProductDataImportToMerchantFacadeInterface $merchantFacade,
        protected MerchantProductDataImportToMerchantStockFacadeInterface $merchantStockFacade,
        protected MerchantProductDataImportToStoreFacadeInterface $storeFacade,
        protected MerchantProductDataImportToCurrencyFacadeInterface $currencyFacade,
        protected MerchantProductDataImportToProductAttributeFacadeInterface $productAttributeFacade
    ) {
    }

    /**
     * @param array<string, list<string>> $possibleCsvHeaders
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, list<string>>
     */
    public function expand(array $possibleCsvHeaders, MerchantTransfer $merchantTransfer): array
    {
        if (!isset($possibleCsvHeaders[MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT])) {
            return $possibleCsvHeaders;
        }

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setWithExpanders(false)
            ->setMerchantReference($merchantTransfer->getMerchantReferenceOrFail());

        $merchantTransfer = $this->merchantFacade->get($merchantCriteriaTransfer)->getMerchants()->getIterator()->current();
        if (!$merchantTransfer) {
            return $possibleCsvHeaders;
        }

        $headers = array_merge(
            $this->merchantProductDataImportConfig->getPossibleCsvHeaders(),
            $this->merchantProductDataImportConfig->getPossibleCsvLocaleHeaders(),
            $this->merchantProductDataImportConfig->getPossibleCsvAttributeHeaders(),
            $this->merchantProductDataImportConfig->getPossibleCsvStockHeaders(),
            $this->merchantProductDataImportConfig->getPossibleCsvPriceHeaders(),
            $this->merchantProductDataImportConfig->getPossibleCsvImageHeaders(),
        );

        $locales = array_values($this->localeFacade->getAvailableLocales());
        $stocks = $this->getMerchantStockNames($merchantTransfer);
        $stores = $this->getStoreNames();
        $currencies = $this->getCurrencyCodes(array_keys($stores));
        $attributes = $this->getAttributeKeys();

        $headers = $this->replacePlaceholders($headers, [
            '{locale}' => $locales,
            '{stock}' => $stocks,
            '{store}' => $stores,
            '{currency}' => $currencies,
            '{attribute}' => $attributes,
        ]);

        $possibleCsvHeaders[MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT] = array_merge(
            $possibleCsvHeaders[MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT],
            $headers,
        );

        return $possibleCsvHeaders;
    }

    /**
     * @param list<string> $headers
     * @param array<string, string|list<string>> $replacements
     *
     * @return list<string>
     */
    protected function replacePlaceholders(array $headers, array $replacements): array
    {
        $result = [];
        foreach ($headers as $header) {
            $result = array_merge($result, $this->expandHeaderPlaceholders($header, $replacements));
        }

        return $result;
    }

    /**
     * @param string $header
     * @param array<string, string|list<string>> $replacements
     *
     * @return list<string>
     */
    protected function expandHeaderPlaceholders(string $header, array $replacements): array
    {
        foreach ($replacements as $placeholder => $values) {
            if (strpos($header, $placeholder) !== false) {
                if (is_array($values)) {
                    $expanded = [];
                    foreach ($values as $value) {
                        $expanded = array_merge(
                            $expanded,
                            $this->expandHeaderPlaceholders(str_replace($placeholder, $value, $header), $replacements),
                        );
                    }

                    return $expanded;
                }
                $header = str_replace($placeholder, $values, $header);
            }
        }

        return [$header];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return list<string>
     */
    protected function getMerchantStockNames(MerchantTransfer $merchantTransfer): array
    {
        $stockNames = [];
        $stockTransfers = $this->merchantStockFacade->get(
            (new MerchantStockCriteriaTransfer())->setIdMerchant($merchantTransfer->getIdMerchantOrFail()),
        )->getStocks();

        foreach ($stockTransfers as $stockTransfer) {
            $stockNames[] = $stockTransfer->getNameOrFail();
        }

        return $stockNames;
    }

    /**
     * @return array<int, string>
     */
    protected function getStoreNames(): array
    {
        $storeNames = [];
        foreach ($this->storeFacade->getAllStores() as $stockTransfer) {
            $storeNames[$stockTransfer->getIdStoreOrFail()] = $stockTransfer->getNameOrFail();
        }

        return $storeNames;
    }

    /**
     * @param list<int> $storeIds
     *
     * @return list<string>
     */
    protected function getCurrencyCodes(array $storeIds): array
    {
        $currencyCodes = [];
        $storeTransfers = array_map(
            static fn (int $idStore): StoreTransfer => (new StoreTransfer())->setIdStore($idStore),
            $storeIds,
        );

        foreach ($this->currencyFacade->expandStoreTransfersWithCurrencies($storeTransfers) as $storeTransfer) {
            foreach ($storeTransfer->getAvailableCurrencyIsoCodes() as $code) {
                $currencyCodes[] = $code;
            }
        }

        return array_unique($currencyCodes);
    }

    /**
     * @return list<string>
     */
    protected function getAttributeKeys(): array
    {
        $attributeKeys = [];
        foreach ($this->productAttributeFacade->getProductAttributeCollection() as $productManagementAttributeTransfer) {
            $attributeKeys[] = $productManagementAttributeTransfer->getKeyOrFail();
        }

        return $attributeKeys;
    }
}
