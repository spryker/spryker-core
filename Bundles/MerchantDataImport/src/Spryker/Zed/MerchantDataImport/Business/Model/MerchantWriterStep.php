<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business\Model;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantDataImport\Business\Model\DataSet\MerchantDataSetInterface;

class MerchantWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantEntity = SpyMerchantQuery::create()
            ->filterByMerchantKey($dataSet[MerchantDataSetInterface::MERCHANT_KEY])
            ->findOneOrCreate();

        $merchantEntity
            ->setName($dataSet[MerchantDataSetInterface::NAME])
            ->setRegistrationNumber($dataSet[MerchantDataSetInterface::REGISTRATION_NUMBER])
            ->setStatus($dataSet[MerchantDataSetInterface::STATUS])
            ->setContactPersonTitle($dataSet[MerchantDataSetInterface::CONTACT_PERSON_TITLE])
            ->setContactPersonFirstName($dataSet[MerchantDataSetInterface::CONTACT_PERSON_FIRST_NAME])
            ->setContactPersonLastName($dataSet[MerchantDataSetInterface::CONTACT_PERSON_LAST_NAME])
            ->setContactPersonPhone($dataSet[MerchantDataSetInterface::CONTACT_PERSON_PHONE])
            ->setEmail($dataSet[MerchantDataSetInterface::EMAIL])
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
        if (!$dataSet[MerchantDataSetInterface::MERCHANT_KEY]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::MERCHANT_KEY . '" is required.');
        }

        if (!$dataSet[MerchantDataSetInterface::NAME]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::NAME . '" is required.');
        }

        if (!$dataSet[MerchantDataSetInterface::REGISTRATION_NUMBER]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::REGISTRATION_NUMBER . '" is required.');
        }

        if (!$dataSet[MerchantDataSetInterface::STATUS]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::STATUS . '" is required.');
        }

        if (!$dataSet[MerchantDataSetInterface::CONTACT_PERSON_TITLE]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::CONTACT_PERSON_TITLE . '" is required.');
        }

        if (!$dataSet[MerchantDataSetInterface::CONTACT_PERSON_FIRST_NAME]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::CONTACT_PERSON_FIRST_NAME . '" is required.');
        }

        if (!$dataSet[MerchantDataSetInterface::CONTACT_PERSON_LAST_NAME]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::CONTACT_PERSON_LAST_NAME . '" is required.');
        }

        if (!$dataSet[MerchantDataSetInterface::CONTACT_PERSON_PHONE]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::CONTACT_PERSON_PHONE . '" is required.');
        }

        if (!$dataSet[MerchantDataSetInterface::EMAIL]) {
            throw new InvalidDataException('"' . MerchantDataSetInterface::EMAIL . '" is required.');
        }
    }
}
