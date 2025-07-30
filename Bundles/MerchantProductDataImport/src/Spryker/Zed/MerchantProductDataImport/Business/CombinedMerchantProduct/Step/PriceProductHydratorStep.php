<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddStoresStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class PriceProductHydratorStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @var string
     */
    public const KEY_PRICE_PRODUCT_TRANSFERS = 'DATA_PRICE_PRODUCT_TRANSFERS';

    /**
     * @var string
     */
    protected const SUFFIX_VALUE_NET = 'value_net';

    /**
     * @var string
     */
    protected const SUFFIX_VALUE_GROSS = 'value_gross';

    /**
     * @var string
     */
    protected const TYPE_PRODUCT_PRICE = 'product_price';

    /**
     * @var string
     */
    protected const TYPE_ABSTRACT_PRODUCT_PRICE = 'abstract_product_price';

    /**
     * @var array<string>
     */
    protected const PRICE_KEY_PATTERNS = [
        MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_PRODUCT_PRICE_STORE_PRICE_TYPE_CURRENCY_VALUE_GROSS,
        MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_PRODUCT_PRICE_STORE_PRICE_TYPE_CURRENCY_VALUE_NET,
        MerchantCombinedProductDataSetInterface::KEY_PRODUCT_PRICE_STORE_PRICE_TYPE_CURRENCY_VALUE_GROSS,
        MerchantCombinedProductDataSetInterface::KEY_PRODUCT_PRICE_STORE_PRICE_TYPE_CURRENCY_VALUE_NET,
    ];

    /**
     * @var array<string, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected static array $pricesColumnKeys = [];

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository
     */
    public function __construct(
        protected readonly MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->isAssignedProductTypeSupported($dataSet)) {
            return;
        }

        $keysToPriceProductMap = $this->getPricesColumnKeys($dataSet);

        $priceProductTransfers = [];
        foreach ($keysToPriceProductMap as $key => $prototypePriceProductTransfer) {
            $this->assertPriceValueIsNumeric($dataSet, $key);

            $keyParts = explode('.', $key);
            $productType = current($keyParts);
            $priceMode = end($keyParts);

            $priceProductTransfer = $this->clonePriceProductTransfer($prototypePriceProductTransfer)->setGroupKey($productType);
            $priceProductTransfer = $priceProductTransfers[$this->generatePriceProductIdentifier($priceProductTransfer)] ?? $priceProductTransfer;

            $moneyValue = (int)$dataSet[$key];
            $this->setMoneyValueAmount($priceMode, $moneyValue, $priceProductTransfer);
            $this->setProductIdentifier($dataSet, $productType, $priceProductTransfer);

            $priceProductTransfers[$this->generatePriceProductIdentifier($priceProductTransfer)] = $priceProductTransfer;
        }

        $dataSet[static::KEY_PRICE_PRODUCT_TRANSFERS] = $priceProductTransfers;
    }

    /**
     * @param string $priceMode
     * @param int|null $amount
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function setMoneyValueAmount(
        string $priceMode,
        ?int $amount,
        PriceProductTransfer $priceProductTransfer
    ): void {
        switch ($priceMode) {
            case static::SUFFIX_VALUE_NET:
                $priceProductTransfer->getMoneyValueOrFail()->setNetAmount($amount);

                break;
            case static::SUFFIX_VALUE_GROSS:
                $priceProductTransfer->getMoneyValueOrFail()->setGrossAmount($amount);

                break;
            default:
                throw new MerchantCombinedProductException(sprintf('Price mode "%s" not supported', $priceMode));
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $productType
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function setProductIdentifier(
        DataSetInterface $dataSet,
        string $productType,
        PriceProductTransfer $priceProductTransfer
    ): void {
        switch ($productType) {
            case static::TYPE_PRODUCT_PRICE:
                $sku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU];
                $idProduct = $this->merchantCombinedProductRepository->getIdProductBySku($sku);
                $priceProductTransfer->setIdProduct($idProduct);

                break;
            case static::TYPE_ABSTRACT_PRODUCT_PRICE:
                $abstractSku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU];
                $idProductAbstract = $this->merchantCombinedProductRepository->getIdProductAbstractByAbstractSku($abstractSku);
                $priceProductTransfer->setIdProductAbstract($idProductAbstract);

                break;
            default:
                throw new MerchantCombinedProductException(sprintf('Price type "%s" unrecognisable', $productType));
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function getPricesColumnKeys(DataSetInterface $dataSet): array
    {
        if (!static::$pricesColumnKeys) {
            $currenciesCodeToIdMap = $dataSet[AddCurrenciesStep::KEY_CURRENCIES];
            $storesNameToIdMap = $dataSet[AddStoresStep::KEY_STORES];
            $priceTypeNameToIdMap = $dataSet[AddPriceTypesStep::KEY_PRICE_TYPES];

            foreach ($priceTypeNameToIdMap as $priceTypeName => $idPriceType) {
                foreach ($currenciesCodeToIdMap as $currencyCode => $idCurrency) {
                    foreach ($storesNameToIdMap as $storeName => $idStore) {
                        $priceProductTransfer = $this->createPriceProductTransfer(
                            $priceTypeName,
                            $idPriceType,
                            $idStore,
                            $idCurrency,
                        );

                        $this->cacheRelevantKeysWithPriceProductPrototype(
                            $dataSet,
                            $priceProductTransfer,
                            $storeName,
                            $currencyCode,
                        );
                    }
                }
            }
        }

        return static::$pricesColumnKeys;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param string $storeName
     * @param string $currencyCode
     *
     * @return void
     */
    protected function cacheRelevantKeysWithPriceProductPrototype(
        DataSetInterface $dataSet,
        PriceProductTransfer $priceProductTransfer,
        string $storeName,
        string $currencyCode
    ): void {
        foreach (static::PRICE_KEY_PATTERNS as $priceKeyPattern) {
            $key = $this->formatPriceKeyByPattern(
                $priceKeyPattern,
                $storeName,
                $priceProductTransfer->getPriceTypeNameOrFail(),
                $currencyCode,
            );
            if (!$dataSet->offsetExists($key)) {
                continue;
            }

            static::$pricesColumnKeys[$key] = $priceProductTransfer;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    protected function generatePriceProductIdentifier(PriceProductTransfer $priceProductTransfer): string
    {
        return sprintf(
            '%s-%d-%d-%s',
            $priceProductTransfer->getPriceTypeName(),
            $priceProductTransfer->getMoneyValueOrFail()->getFkStore(),
            $priceProductTransfer->getMoneyValueOrFail()->getFkCurrency(),
            $priceProductTransfer->getGroupKey(),
        );
    }

    /**
     * @param string $priceTypeName
     * @param int $idPriceType
     * @param int $idStore
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        string $priceTypeName,
        int $idPriceType,
        int $idStore,
        int $idCurrency
    ): PriceProductTransfer {
        return (new PriceProductTransfer())
            ->setPriceTypeName($priceTypeName)
            ->setFkPriceType($idPriceType)
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setFkStore($idStore)
                    ->setFkCurrency($idCurrency),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function clonePriceProductTransfer(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return (new PriceProductTransfer())->fromArray($priceProductTransfer->toArray(), true);
    }

    /**
     * @param string $keyPattern
     * @param string $store
     * @param string $priceType
     * @param string $currency
     *
     * @return string
     */
    protected function formatPriceKeyByPattern(
        string $keyPattern,
        string $store,
        string $priceType,
        string $currency
    ): string {
        return strtr($keyPattern, [
            MerchantCombinedProductDataSetInterface::PLACEHOLDER_STORE => $store,
            MerchantCombinedProductDataSetInterface::PLACEHOLDER_PRICE_TYPE => strtolower($priceType),
            MerchantCombinedProductDataSetInterface::PLACEHOLDER_CURRENCY => $currency,
        ]);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $key
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function assertPriceValueIsNumeric(DataSetInterface $dataSet, string $key): void
    {
        if (!$dataSet[$key] || is_numeric($dataSet[$key])) {
            return;
        }

        throw new MerchantCombinedProductException(sprintf(
            'Price value of "%s" must be numeric. Provided value: "%s"',
            $key,
            $dataSet[$key],
        ));
    }

    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_CONCRETE,
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
        ];
    }
}
