<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionGroup;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroupQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataSet\MerchantCommissionGroupDataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\Validator\DataSetValidatorInterface;

class MerchantCommissionGroupWriteDataImportStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommissionDataImport\Business\Validator\DataSetValidatorInterface
     */
    protected DataSetValidatorInterface $dataSetValidator;

    /**
     * @param \Spryker\Zed\MerchantCommissionDataImport\Business\Validator\DataSetValidatorInterface $dataSetValidator
     */
    public function __construct(DataSetValidatorInterface $dataSetValidator)
    {
        $this->dataSetValidator = $dataSetValidator;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->dataSetValidator->assertNoEmptyColumns($dataSet);

        $merchantCommissionGroupEntity = $this->getMerchantCommissionGroupQuery()
            ->filterByKey($dataSet[MerchantCommissionGroupDataSetInterface::COLUMN_KEY])
            ->findOneOrCreate();

        $merchantCommissionGroupEntity
            ->fromArray($dataSet->getArrayCopy())
            ->save();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroupQuery
     */
    protected function getMerchantCommissionGroupQuery(): SpyMerchantCommissionGroupQuery
    {
        return SpyMerchantCommissionGroupQuery::create();
    }
}
