<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class PreparePriceDataStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const WRONG_PRICE_EXCEPTION_MESSAGE = 'Price "%s" is wrong';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET] =
            empty($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET])
                ? null
                : (int)$dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET];

        $dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS] =
            empty($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS])
                ? null
                : (int)$dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS];

        if (!$this->isPriceValid($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET])) {
            throw new InvalidDataException(sprintf(
                static::WRONG_PRICE_EXCEPTION_MESSAGE,
                $dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET],
            ));
        }

        if (!$this->isPriceValid($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS])) {
            throw new InvalidDataException(sprintf(
                static::WRONG_PRICE_EXCEPTION_MESSAGE,
                $dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS],
            ));
        }
    }

    /**
     * @param int|null $price
     *
     * @return bool
     */
    protected function isPriceValid(?int $price): bool
    {
        return $price === null || ($price !== null && $price > 0);
    }
}
