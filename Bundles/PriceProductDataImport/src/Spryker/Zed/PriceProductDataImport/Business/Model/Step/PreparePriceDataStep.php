<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\PriceProductDataImport\Business\Model\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductDataImport\Business\Exception\InvalidPriceDataKeyException;
use Spryker\Zed\PriceProductDataImport\Business\Model\DataSet\PriceProductDataSet;
use Spryker\Zed\PriceProductDataImport\Dependency\Facade\PriceProductDataImportToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductDataImport\Dependency\Service\PriceProductDataImportToUtilEncodingServiceInterface;

class PreparePriceDataStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $priceDataKeysCache = [];

    /**
     * @var bool
     */
    protected $isDataKeysCachePrepared = false;

    /**
     * @var \Spryker\Zed\PriceProductDataImport\Dependency\Facade\PriceProductDataImportToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductDataImport\Dependency\Service\PriceProductDataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\PriceProductDataImport\Dependency\Facade\PriceProductDataImportToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductDataImport\Dependency\Service\PriceProductDataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PriceProductDataImportToPriceProductFacadeInterface $priceProductFacade,
        PriceProductDataImportToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $this->preparePriceDataKeysCache($dataSet);
        $dataSet[PriceProductDataSet::KEY_PRICE_DATA] = $this->getPriceData($dataSet);
        if ($dataSet[PriceProductDataSet::KEY_PRICE_DATA] === null) {
            $dataSet[PriceProductDataSet::KEY_PRICE_DATA_CHECKSUM] = null;

            return;
        }
        $dataSet[PriceProductDataSet::KEY_PRICE_DATA_CHECKSUM] = $this->priceProductFacade
            ->generatePriceDataChecksum($dataSet[PriceProductDataSet::KEY_PRICE_DATA]);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function preparePriceDataKeysCache(DataSetInterface $dataSet): void
    {
        if ($this->isDataKeysCachePrepared) {
            return;
        }
        foreach ($dataSet as $key => $value) {
            if (!$this->isPriceDataKey($key)) {
                continue;
            }
            $this->priceDataKeysCache[$key] = $this->getPriceDataKey($key);
        }
        $this->isDataKeysCachePrepared = true;
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Zed\PriceProductDataImport\Business\Exception\InvalidPriceDataKeyException
     *
     * @return string
     */
    protected function getPriceDataKey(string $key): string
    {
        $keyParts = explode('.', $key);
        if (count($keyParts) < 2) {
            throw new InvalidPriceDataKeyException(
                sprintf(
                    'Price data key "%s" has invalid format. Should be in following format: "price_data.some_key"',
                    $key
                )
            );
        }

        return $keyParts[1];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isPriceDataKey(string $key): bool
    {
        return mb_strpos($key, PriceProductDataSet::KEY_PRICE_DATA_PREFIX) === 0;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return null|string
     */
    protected function getPriceData(DataSetInterface $dataSet): ?string
    {
        $priceData = [];

        foreach ($this->priceDataKeysCache as $dataSetKey => $priceDataKey) {
            $priceData = $this->addPriceDataValue($priceData, $priceDataKey, $dataSet[$dataSetKey]);
        }

        if (empty($priceData)) {
            return null;
        }

        return $this->utilEncodingService->encodeJson($priceData);
    }

    /**
     * @param array $priceData
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    protected function addPriceDataValue(array $priceData, string $key, string $value): array
    {
        if (empty($value)) {
            return $priceData;
        }

        $priceData[$key] = $this->utilEncodingService->decodeJson($value, true);

        return $priceData;
    }
}
