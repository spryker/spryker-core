<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionMerchant;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionMerchantQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataSet\MerchantCommissionMerchantDataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\Validator\DataSetValidatorInterface;

class MerchantCommissionMerchantWriteDataImportStep implements DataImportStepInterface
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

        $merchantCommissionEntity = $this->getMerchantCommissionMerchantQuery()
            ->filterByFkMerchant($dataSet[MerchantCommissionMerchantDataSetInterface::ID_MERCHANT])
            ->filterByFkMerchantCommission($dataSet[MerchantCommissionMerchantDataSetInterface::ID_MERCHANT_COMMISSION])
            ->findOneOrCreate();
        $merchantCommissionEntity->save();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionMerchantQuery
     */
    protected function getMerchantCommissionMerchantQuery(): SpyMerchantCommissionMerchantQuery
    {
        return SpyMerchantCommissionMerchantQuery::create();
    }
}
