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

class MerchantCombinedProductOfferStockExtractorStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const STOCK_KEY_PREFIX = 'stock.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_STOCK_DATA_KEY = 'Invalid stock data key "%key%".';

    /**
     * @var string
     */
    protected const PARAM_KEY = '%key%';

    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CombinedProductOfferDataSetInterface::DATA_PRODUCT_OFFER_STOCKS] = $this->extractProductOfferStocks($dataSet);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function extractProductOfferStocks(DataSetInterface $dataSet): array
    {
        $productOfferStocks = [];
        $stockDataSetKeys = $this->getStockDataSetKeys($dataSet);

        foreach ($stockDataSetKeys as $key) {
            [, $warehouseName, $attribute] = $this->getProductOfferStockKeyParts($key);

            $productOfferStocks[$warehouseName][$attribute] = $dataSet[$key] ?? null;
        }

        return $productOfferStocks;
    }

    /**
     * @return list<string>
     */
    protected function getStockDataSetKeys(DataSetInterface $dataSet): array
    {
        $dataSetKeys = array_keys($dataSet->getArrayCopy());

        return array_filter($dataSetKeys, fn ($key) => str_starts_with($key, static::STOCK_KEY_PREFIX));
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     *
     * @return list<string>
     */
    protected function getProductOfferStockKeyParts(string $key): array
    {
        $keyParts = explode('.', $key);

        if (count($keyParts) !== 3) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_INVALID_STOCK_DATA_KEY)
                    ->setParameters([static::PARAM_KEY => $key]),
            );
        }

        return $keyParts;
    }
}
