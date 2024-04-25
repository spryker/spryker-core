<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionStore;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataSet\MerchantCommissionStoreDataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\Validator\DataSetValidatorInterface;

class MerchantCommissionStoreWriteDataImportStep implements DataImportStepInterface
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

        $merchantCommissionStoreEntity = $this->getMerchantCommissionStoreQuery()
            ->filterByFkStore($dataSet[MerchantCommissionStoreDataSetInterface::ID_STORE])
            ->filterByFkMerchantCommission($dataSet[MerchantCommissionStoreDataSetInterface::ID_MERCHANT_COMMISSION])
            ->findOneOrCreate();
        $merchantCommissionStoreEntity->save();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionStoreQuery
     */
    protected function getMerchantCommissionStoreQuery(): SpyMerchantCommissionStoreQuery
    {
        return SpyMerchantCommissionStoreQuery::create();
    }
}
