<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\Exception\InvalidPriceDataKeyException;
use Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface;

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
     * @var \Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PriceProductOfferDataImportToPriceProductFacadeInterface $priceProductFacade,
        DataImportToUtilEncodingServiceInterface $utilEncodingService
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
        $priceData = $this->getPriceData($dataSet);
        if ($priceData === null) {
            $dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA] = null;
            $dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA_CHECKSUM] = null;

            return;
        }
        $dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA] = $this->utilEncodingService->encodeJson($priceData);
        $dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA_CHECKSUM] = $this->priceProductFacade
            ->generatePriceDataChecksum($priceData);
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
     * @throws \Spryker\Zed\PriceProductOfferDataImport\Business\Exception\InvalidPriceDataKeyException
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
        return mb_strpos($key, PriceProductOfferDataSetInterface::KEY_PRICE_DATA_PREFIX) === 0;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array|null
     */
    protected function getPriceData(DataSetInterface $dataSet): ?array
    {
        $priceData = [];

        foreach ($this->priceDataKeysCache as $dataSetKey => $priceDataKey) {
            $priceData = $this->addPriceDataValue($priceData, $priceDataKey, $dataSet[$dataSetKey]);
        }

        if (empty($priceData)) {
            return null;
        }

        return $priceData;
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
