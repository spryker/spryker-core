<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business\Address\Step;

use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddressQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantProfileDataImport\Business\Address\DataSet\MerchantProfileAddressDataSetInterface;

class MerchantProfileAddressWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantProfileAddressDataSetInterface::ADDRESS1,
        MerchantProfileAddressDataSetInterface::ADDRESS2,
        MerchantProfileAddressDataSetInterface::CITY,
        MerchantProfileAddressDataSetInterface::ZIP_CODE,
        MerchantProfileAddressDataSetInterface::MERCHANT_KEY,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantProfileAddressEntity = SpyMerchantProfileAddressQuery::create()
            ->filterByAddress1($dataSet[MerchantProfileAddressDataSetInterface::ADDRESS1])
            ->filterByAddress2($dataSet[MerchantProfileAddressDataSetInterface::ADDRESS2])
            ->filterByAddress3($dataSet[MerchantProfileAddressDataSetInterface::ADDRESS3])
            ->filterByCity($dataSet[MerchantProfileAddressDataSetInterface::CITY])
            ->filterByZipCode($dataSet[MerchantProfileAddressDataSetInterface::ZIP_CODE])
            ->filterByFkMerchantProfile($dataSet[MerchantProfileAddressDataSetInterface::ID_MERCHANT_PROFILE])
            ->filterByFkCountry($dataSet[MerchantProfileAddressDataSetInterface::ID_COUNTRY])
            ->findOneOrCreate();

        $merchantProfileAddressEntity
            ->setAddress1($dataSet[MerchantProfileAddressDataSetInterface::ADDRESS1])
            ->setAddress2($dataSet[MerchantProfileAddressDataSetInterface::ADDRESS2])
            ->setAddress3($dataSet[MerchantProfileAddressDataSetInterface::ADDRESS3])
            ->setCity($dataSet[MerchantProfileAddressDataSetInterface::CITY])
            ->setZipCode($dataSet[MerchantProfileAddressDataSetInterface::ZIP_CODE])
            ->setFkMerchantProfile($dataSet[MerchantProfileAddressDataSetInterface::ID_MERCHANT_PROFILE])
            ->setFkCountry($dataSet[MerchantProfileAddressDataSetInterface::ID_COUNTRY])
            ->save();

        $this->addPublishEvents(MerchantEvents::MERCHANT_PUBLISH, $merchantProfileAddressEntity->getSpyMerchantProfile()->getFkMerchant());
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
            throw new InvalidDataException(sprintf('"%s" is required.', $requiredDataSetKey));
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
        if (!$dataSet[MerchantProfileAddressDataSetInterface::COUNTRY_ISO2_CODE] && !$dataSet[MerchantProfileAddressDataSetInterface::COUNTRY_ISO3_CODE]) {
            throw new InvalidDataException(
                sprintf(
                    '"%s" or "%s" are required.',
                    MerchantProfileAddressDataSetInterface::COUNTRY_ISO2_CODE,
                    MerchantProfileAddressDataSetInterface::COUNTRY_ISO3_CODE
                )
            );
        }
    }
}
