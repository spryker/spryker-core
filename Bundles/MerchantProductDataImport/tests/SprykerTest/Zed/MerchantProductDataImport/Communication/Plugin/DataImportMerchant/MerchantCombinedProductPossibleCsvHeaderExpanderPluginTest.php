<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant\MerchantCombinedProductPossibleCsvHeaderExpanderPlugin;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Zed\MerchantProductDataImport\MerchantProductDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductDataImport
 * @group Communication
 * @group Plugin
 * @group DataImportMerchant
 * @group MerchantCombinedProductPossibleCsvHeaderExpanderPluginTest
 * Add your own group annotations below this line
 */
class MerchantCombinedProductPossibleCsvHeaderExpanderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT
     *
     * @var string
     */
    protected const IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT = 'merchant-combined-product';

    /**
     * @var \SprykerTest\Zed\MerchantProductDataImport\MerchantProductDataImportCommunicationTester
     */
    protected MerchantProductDataImportCommunicationTester $tester;

    /**
     * @dataProvider expandHeadersDataProvider
     *
     * @param string $configMethodName
     * @param array $configMethodValue
     *
     * @return void
     */
    public function testShouldExpandHeadersWithPossibleCsvHeaders(
        string $configMethodName,
        array $configMethodValue
    ): void {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveMerchantStock([
            MerchantStockTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantStockTransfer::ID_STOCK => $stockTransfer->getIdStock(),
        ]);

        // Act
        $possibleCsvHeaders = $this->getPluginWithMockedConfigMethod($configMethodName, $configMethodValue)
            ->expand([static::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT => []], $merchantTransfer);

        // Assert
        $stores = $this->getStoreNames();
        $replacements = [
            '{stock}' => $stockTransfer->getName(),
            '{locale}' => $this->getLocaleFacade()->getAvailableLocales(),
            '{attribute}' => $this->getAttributeKeys(),
            '{store}' => $stores,
            '{currency}' => $this->getCurrencyCodes(array_keys($stores)),
        ];

        $this->assertEqualsCanonicalizing(
            $this->replacePlaceholders($configMethodValue, $replacements),
            $possibleCsvHeaders[static::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT],
        );
    }

    /**
     * @return void
     */
    public function testShouldNotExpandHeadersForNonMerchantCombinedProductImporterTypes(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveMerchantStock([
            MerchantStockTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantStockTransfer::ID_STOCK => $stockTransfer->getIdStock(),
        ]);

        // Act
        $possibleCsvHeaders = $this->getPluginWithMockedConfigMethod('getPossibleCsvHeaders', ['abstract_sku'])
            ->expand(['merchant-product' => []], $merchantTransfer);

        // Assert
        $this->assertEmpty($possibleCsvHeaders['merchant-product']);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenMerchantReferenceNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "merchantReference" of transfer `Generated\Shared\Transfer\MerchantTransfer` is null.');

        // Act
        $this->getPluginWithMockedConfigMethod('getPossibleCsvHeaders', ['abstract_sku'])
            ->expand([static::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT => []], new MerchantTransfer());
    }

    /**
     * @return void
     */
    public function testShouldNotExpandForUndefinedMerchant(): void
    {
        // Act
        $possibleCsvHeaders = $this->getPluginWithMockedConfigMethod('getPossibleCsvHeaders', ['abstract_sku'])
            ->expand([static::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT => []], (new MerchantTransfer())->setMerchantReference('undefined'));

        // Assert
        $this->assertEmpty($possibleCsvHeaders[static::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT]);
    }

    /**
     * @return array<string, array<string, list<string>|string>>
     */
    public static function expandHeadersDataProvider(): array
    {
        return [
            'possibleCsvHeaders' => [
                'getPossibleCsvHeaders',
                [
                    'abstract_sku',
                    'is_active',
                    'concrete_sku',
                    'store_relations',
                    'product_abstract.categories',
                    'product_abstract.tax_set_name',
                    'product_abstract.new_from',
                    'product_abstract.new_to',
                    'product.is_quantity_splittable',
                    'product.assigned_product_type',
                ],
            ],
            'possibleCsvStockHeaders' => [
                'getPossibleCsvStockHeaders',
                [
                    'product.{stock}.quantity',
                    'product.{stock}.is_never_out_of_stock',
                ],
            ],
            'possibleCsvLocaleHeaders' => [
                'getPossibleCsvLocaleHeaders',
                [
                    'product_abstract.name.{locale}',
                    'product_abstract.description.{locale}',
                    'product_abstract.meta_title.{locale}',
                    'product_abstract.meta_description.{locale}',
                    'product_abstract.meta_keywords.{locale}',
                    'product_abstract.url.{locale}',
                    'product.name.{locale}',
                    'product.description.{locale}',
                    'product.is_searchable.{locale}',
                ],
            ],
            'possibleCsvAttributeHeaders' => [
                'getPossibleCsvAttributeHeaders',
                [
                    'product.{attribute}',
                    'product.{attribute}.{locale}',
                ],
            ],
            'possibleCsvPriceHeaders' => [
                'getPossibleCsvPriceHeaders',
                [
                    'product_price.{store}.default.{currency}.value_net',
                    'product_price.{store}.default.{currency}.value_gross',
                    'abstract_product_price.{store}.default.{currency}.value_net',
                    'abstract_product_price.{store}.default.{currency}.value_gross',
                ],
            ],
            'possibleCsvImageHeaders' => [
                'getPossibleCsvImageHeaders',
                [
                    'product_image.DEFAULT.default.sort_order',
                    'product_image.DEFAULT.default.external_url_large',
                    'product_image.DEFAULT.default.external_url_small',
                    'abstract_product_image.DEFAULT.default.sort_order',
                    'abstract_product_image.DEFAULT.default.external_url_small',
                    'abstract_product_image.DEFAULT.default.external_url_large',
                    'abstract_product_image.{locale}.default.sort_order',
                    'abstract_product_image.{locale}.default.external_url_small',
                    'abstract_product_image.{locale}.default.external_url_large',
                ],
            ],
        ];
    }

    /**
     * @param string|null $configMethodName
     * @param list<string>|null $configMethodValue
     *
     * @return \Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant\MerchantCombinedProductPossibleCsvHeaderExpanderPlugin
     */
    protected function getPluginWithMockedConfigMethod(
        ?string $configMethodName = null,
        ?array $configMethodValue = []
    ): MerchantCombinedProductPossibleCsvHeaderExpanderPlugin {
        if (!$configMethodName) {
            return new MerchantCombinedProductPossibleCsvHeaderExpanderPlugin();
        }

        $merchantProductDataImportConfig = $this->getMockBuilder(MerchantProductDataImportConfig::class)->getMock();
        $merchantProductDataImportConfig
            ->method($configMethodName)
            ->willReturn($configMethodValue);

        $businessFactory = $this->tester->getFactory();
        $businessFactory->setConfig($merchantProductDataImportConfig);

        return (new MerchantCombinedProductPossibleCsvHeaderExpanderPlugin())
            ->setBusinessFactory($businessFactory);
    }

    /**
     * @return list<string>
     */
    protected function getAttributeKeys(): array
    {
        $attributeKeys = [];
        foreach ($this->getProductAttributeFacade()->getProductAttributeCollection() as $productManagementAttributeTransfer) {
            $attributeKeys[] = $productManagementAttributeTransfer->getKeyOrFail();
        }

        return $attributeKeys;
    }

    /**
     * @return array<int, string>
     */
    protected function getStoreNames(): array
    {
        $storeNames = [];
        foreach ($this->getStoreFacade()->getAllStores() as $stockTransfer) {
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

        foreach ($this->getCurrencyFacade()->expandStoreTransfersWithCurrencies($storeTransfers) as $storeTransfer) {
            foreach ($storeTransfer->getAvailableCurrencyIsoCodes() as $code) {
                $currencyCodes[] = $code;
            }
        }

        return array_unique($currencyCodes);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->tester->getLocator()->locale()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected function getProductAttributeFacade(): ProductAttributeFacadeInterface
    {
        return $this->tester->getLocator()->productAttribute()->facade();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->tester->getLocator()->store()->facade();
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function getCurrencyFacade(): CurrencyFacadeInterface
    {
        return $this->tester->getLocator()->currency()->facade();
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
}
