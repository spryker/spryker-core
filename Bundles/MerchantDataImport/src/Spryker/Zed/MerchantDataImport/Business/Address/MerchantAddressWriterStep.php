<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business\Address;

use Orm\Zed\Merchant\Persistence\SpyMerchantAddressQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantDataImport\Business\Address\DataSet\MerchantAddressDataSetInterface;

class MerchantAddressWriterStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantAddressDataSetInterface::KEY,
        MerchantAddressDataSetInterface::ADDRESS1,
        MerchantAddressDataSetInterface::ADDRESS2,
        MerchantAddressDataSetInterface::CITY,
        MerchantAddressDataSetInterface::ZIP_CODE,
        MerchantAddressDataSetInterface::MERCHANT_KEY,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantEntity = SpyMerchantAddressQuery::create()
            ->filterByKey($dataSet[MerchantAddressDataSetInterface::KEY])
            ->findOneOrCreate();

        $merchantEntity
            ->setAddress1($dataSet[MerchantAddressDataSetInterface::ADDRESS1])
            ->setAddress2($dataSet[MerchantAddressDataSetInterface::ADDRESS2])
            ->setAddress3($dataSet[MerchantAddressDataSetInterface::ADDRESS3])
            ->setCity($dataSet[MerchantAddressDataSetInterface::CITY])
            ->setZipCode($dataSet[MerchantAddressDataSetInterface::ZIP_CODE])
            ->setFkMerchant($dataSet[MerchantAddressDataSetInterface::ID_MERCHANT])
            ->setFkCountry($dataSet[MerchantAddressDataSetInterface::ID_COUNTRY])
            ->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        $this->validateSimpleRequiredDataSet($dataSet);
        $this->validateCombinedRequiredDataSet($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function validateSimpleRequiredDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            $this->validateRequireDataSetByKey($dataSet, $requiredDataSetKey);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $requiredDataSetKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateRequireDataSetByKey(DataSetInterface $dataSet, string $requiredDataSetKey): void
    {
        if (!$dataSet[$requiredDataSetKey]) {
            throw new InvalidDataException('"' . $requiredDataSetKey . '" is required.');
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateCombinedRequiredDataSet(DataSetInterface $dataSet): void
    {
        if (!$dataSet[MerchantAddressDataSetInterface::COUNTRY_ISO2_CODE] && !$dataSet[MerchantAddressDataSetInterface::COUNTRY_ISO3_CODE]) {
            throw new InvalidDataException('"' . MerchantAddressDataSetInterface::COUNTRY_ISO2_CODE . '" or "' . MerchantAddressDataSetInterface::COUNTRY_ISO3_CODE . '" are required.');
        }
    }
}
