<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommission;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataSet\MerchantCommissionDataSetInterface;

class MerchantCommissionWriteDataImportStep implements DataImportStepInterface
{
    /**
     * @var list<string>
     */
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantCommissionDataSetInterface::COLUMN_KEY,
        MerchantCommissionDataSetInterface::COLUMN_NAME,
        MerchantCommissionDataSetInterface::COLUMN_CALCULATOR_TYPE_PLUGIN,
        MerchantCommissionDataSetInterface::COLUMN_IS_ACTIVE,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assertNoEmptyRequiredColumns($dataSet);

        $merchantCommissionEntity = $this->getMerchantCommissionQuery()
            ->filterByKey($dataSet[MerchantCommissionDataSetInterface::COLUMN_KEY])
            ->findOneOrCreate();

        $merchantCommissionEntity
            ->fromArray($dataSet->getArrayCopy())
            ->setFkMerchantCommissionGroup($dataSet[MerchantCommissionDataSetInterface::ID_MERCHANT_COMMISSION_GROUP])
            ->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function assertNoEmptyRequiredColumns(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            if ($dataSet[$requiredDataSetKey] === '') {
                throw new InvalidDataException(
                    sprintf('"%s" is required.', $requiredDataSetKey),
                );
            }
        }
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    protected function getMerchantCommissionQuery(): SpyMerchantCommissionQuery
    {
        return SpyMerchantCommissionQuery::create();
    }
}
