<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;

class MerchantCombinedProductOfferPriceExtractorStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_STORE = 'store';

    /**
     * @var string
     */
    public const KEY_PRICE_TYPE = 'price_type';

    /**
     * @var string
     */
    public const KEY_CURRENCY = 'currency';

    /**
     * @var string
     */
    public const KEY_VALUE = 'value';

    /**
     * @var string
     */
    public const KEY_VALUE_GROSS = 'value_gross';

    /**
     * @var string
     */
    public const KEY_VALUE_NET = 'value_net';

    /**
     * @var string
     */
    public const KEY_VALUE_TYPE = 'value_type';

    /**
     * @var string
     */
    protected const PRICE_KEY_PREFIX = 'price.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_PRICE_DATA_KEY = 'Invalid price data key "%key%".';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MISSING_PRICE_KEY = 'The data set must contain both net and gross prices for product offer.';

    /**
     * @var string
     */
    protected const PARAM_KEY = '%key%';

    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferPriceDataSetKeys = $this->getProductOfferPriceDataSetKeys($dataSet);
        $this->validateProductOfferPriceKeys($productOfferPriceDataSetKeys);

        $dataSet[CombinedProductOfferDataSetInterface::DATA_PRODUCT_OFFER_PRICES] = $this->extractProductOfferPrices(
            $dataSet,
            $productOfferPriceDataSetKeys,
        );
    }

    /**
     * @param list<string> $productOfferPriceDataSetKeys
     *
     * @return array<string, array<string, mixed>>
     */
    protected function extractProductOfferPrices(DataSetInterface $dataSet, array $productOfferPriceDataSetKeys): array
    {
        $productOfferPrices = [];

        foreach ($productOfferPriceDataSetKeys as $dataSetKey) {
            [, $store, $priceType, $currency, $valueType] = $this->getProductOfferPriceKeyParts($dataSetKey);

            $productOfferPrices[] = [
                static::KEY_STORE => $store,
                static::KEY_PRICE_TYPE => $priceType,
                static::KEY_CURRENCY => $currency,
                static::KEY_VALUE => $dataSet[$dataSetKey],
                static::KEY_VALUE_TYPE => $valueType,
            ];
        }

        return $this->groupProductOfferPrices($productOfferPrices);
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     *
     * @return list<string>
     */
    protected function getProductOfferPriceKeyParts(string $dataSetKey): array
    {
        $keyParts = explode('.', $dataSetKey);

        if (count($keyParts) !== 5) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_INVALID_PRICE_DATA_KEY)
                    ->setParameters([static::PARAM_KEY => $dataSetKey]),
            );
        }

        return $keyParts;
    }

    /**
     * @param list<array<string, mixed>> $productOfferPrices
     *
     * @return array<string, array<string, mixed>>
     */
    protected function groupProductOfferPrices(array $productOfferPrices): array
    {
        $groupedPrices = [];

        foreach ($productOfferPrices as $priceData) {
            $groupKey = implode('-', [
                $priceData[static::KEY_STORE],
                $priceData[static::KEY_PRICE_TYPE],
                $priceData[static::KEY_CURRENCY],
            ]);

            $groupedPrices[$groupKey] ??= [
                static::KEY_STORE => $priceData[static::KEY_STORE],
                static::KEY_PRICE_TYPE => $priceData[static::KEY_PRICE_TYPE],
                static::KEY_CURRENCY => $priceData[static::KEY_CURRENCY],
            ];

            if ($priceData[static::KEY_VALUE_TYPE] === static::KEY_VALUE_NET) {
                $groupedPrices[$groupKey][static::KEY_VALUE_NET] = $priceData[static::KEY_VALUE];
            }

            if ($priceData[static::KEY_VALUE_TYPE] === static::KEY_VALUE_GROSS) {
                $groupedPrices[$groupKey][static::KEY_VALUE_GROSS] = $priceData[static::KEY_VALUE];
            }
        }

        return $groupedPrices;
    }

    /**
     * @return list<string>
     */
    protected function getProductOfferPriceDataSetKeys(DataSetInterface $dataSet): array
    {
        $dataSetKeys = array_keys($dataSet->getArrayCopy());

        return array_filter($dataSetKeys, fn ($key) => str_starts_with($key, static::PRICE_KEY_PREFIX));
    }

    /**
     * @param list<string> $dataSetKeys
     *
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function validateProductOfferPriceKeys(array $dataSetKeys): void
    {
        $priceValueTypesIndexedByProductOfferPriceGroupKey = $this->getPriceValueTypesIndexedByGroupKey($dataSetKeys);

        foreach ($priceValueTypesIndexedByProductOfferPriceGroupKey as $valueTypes) {
            if (array_diff([static::KEY_VALUE_GROSS, static::KEY_VALUE_NET], $valueTypes)) {
                throw MerchantCombinedProductOfferException::createWithError(
                    (new ErrorTransfer())
                        ->setMessage(static::ERROR_MESSAGE_MISSING_PRICE_KEY),
                );
            }
        }
    }

    /**
     * @param array<int, string> $productOfferPriceKeys
     *
     * @return array<string, array<int, string>>
     */
    protected function getPriceValueTypesIndexedByGroupKey(array $productOfferPriceKeys): array
    {
        $valueTypes = [];

        foreach ($productOfferPriceKeys as $index => $productOfferPriceKey) {
            [, $store, $priceType, $currency, $valueType] = $this->getProductOfferPriceKeyParts($productOfferPriceKey);

            $groupKey = implode('-', [
                $store,
                $priceType,
                $currency,
            ]);

            $valueTypes[$groupKey][$index] = $valueType;
        }

        return $valueTypes;
    }
}
