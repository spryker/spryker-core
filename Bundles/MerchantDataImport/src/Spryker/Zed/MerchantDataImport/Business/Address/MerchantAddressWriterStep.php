<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business\Address;

use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Merchant\Persistence\Propel\SpyMerchantAddressQuery;
use Spryker\Zed\MerchantDataImport\Business\Address\DataSet\MerchantAddressDataSetInterface;
use Spryker\Zed\MerchantDataImport\Business\Model\DataSet\MerchantDataSetInterface;

class MerchantAddressWriterStep implements DataImportStepInterface
{
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
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        if (!$dataSet[MerchantAddressDataSetInterface::KEY]) {
            throw new InvalidDataException('"' . MerchantAddressDataSetInterface::KEY . '" is required.');
        }

        if (!$dataSet[MerchantAddressDataSetInterface::ADDRESS1]) {
            throw new InvalidDataException('"' . MerchantAddressDataSetInterface::ADDRESS1 . '" is required.');
        }

        if (!$dataSet[MerchantAddressDataSetInterface::ADDRESS2]) {
            throw new InvalidDataException('"' . MerchantAddressDataSetInterface::ADDRESS2 . '" is required.');
        }

        if (!$dataSet[MerchantAddressDataSetInterface::CITY]) {
            throw new InvalidDataException('"' . MerchantAddressDataSetInterface::CITY . '" is required.');
        }

        if (!$dataSet[MerchantAddressDataSetInterface::ZIP_CODE]) {
            throw new InvalidDataException('"' . MerchantAddressDataSetInterface::ZIP_CODE . '" is required.');
        }

        if (!$dataSet[MerchantAddressDataSetInterface::COUNTRY_ISO2_CODE] && !$dataSet[MerchantAddressDataSetInterface::COUNTRY_ISO3_CODE]) {
            throw new InvalidDataException('"' . MerchantAddressDataSetInterface::COUNTRY_ISO2_CODE . '" or "' . MerchantAddressDataSetInterface::COUNTRY_ISO3_CODE . '" are required.');
        }

        if (!$dataSet[MerchantDataSetInterface::MERCHANT_KEY]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::MERCHANT_KEY . '" is required.');
        }
    }
}
