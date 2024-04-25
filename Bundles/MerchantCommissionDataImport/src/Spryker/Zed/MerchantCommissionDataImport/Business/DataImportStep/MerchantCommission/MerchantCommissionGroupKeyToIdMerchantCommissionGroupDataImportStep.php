<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommission;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroupQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataSet\MerchantCommissionDataSetInterface;

class MerchantCommissionGroupKeyToIdMerchantCommissionGroupDataImportStep implements DataImportStepInterface
{
    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionGroupTableMap::COL_ID_MERCHANT_COMMISSION_GROUP
     *
     * @var string
     */
    protected const COL_ID_MERCHANT_COMMISSION_GROUP = 'spy_merchant_commission_group.id_merchant_commission_group';

    /**
     * @var array<string, int>
     */
    protected array $merchantCommissionGroupIdsIndexedByKey = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $merchantCommissionGroupKey */
        $merchantCommissionGroupKey = $dataSet[MerchantCommissionDataSetInterface::COLUMN_MERCHANT_COMMISSION_GROUP_KEY];
        if (!isset($this->merchantCommissionGroupIdsIndexedByKey[$merchantCommissionGroupKey])) {
            $this->merchantCommissionGroupIdsIndexedByKey[$merchantCommissionGroupKey] = $this->getIdMerchantCommissionGroupByKey($merchantCommissionGroupKey);
        }

        $dataSet[MerchantCommissionDataSetInterface::ID_MERCHANT_COMMISSION_GROUP] = $this->merchantCommissionGroupIdsIndexedByKey[$merchantCommissionGroupKey];
    }

    /**
     * @param string $merchantCommissionGroupKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdMerchantCommissionGroupByKey(string $merchantCommissionGroupKey): int
    {
        /** @var int $idMerchantCommissionGroup */
        $idMerchantCommissionGroup = $this->getMerchantCommissionGroupQuery()
            ->select([static::COL_ID_MERCHANT_COMMISSION_GROUP])
            ->findOneByKey($merchantCommissionGroupKey);

        if (!$idMerchantCommissionGroup) {
            throw new EntityNotFoundException(
                sprintf('Could not find Merchant Commission Group by the key "%s"', $merchantCommissionGroupKey),
            );
        }

        return $idMerchantCommissionGroup;
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroupQuery
     */
    protected function getMerchantCommissionGroupQuery(): SpyMerchantCommissionGroupQuery
    {
        return SpyMerchantCommissionGroupQuery::create();
    }
}
