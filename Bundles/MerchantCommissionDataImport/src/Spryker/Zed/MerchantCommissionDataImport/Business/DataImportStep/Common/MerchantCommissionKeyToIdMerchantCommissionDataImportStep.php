<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\Common;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataSet\MerchantCommissionStoreDataSetInterface;

class MerchantCommissionKeyToIdMerchantCommissionDataImportStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const ID_MERCHANT_COMMISSION = 'id_merchant_commission';

    /**
     * @uses \Orm\Zed\MerchantCommission\Persistence\Map\SpyMerchantCommissionTableMap::COL_ID_MERCHANT_COMMISSION
     *
     * @var string
     */
    protected const COL_ID_MERCHANT_COMMISSION = 'spy_merchant_commission.id_merchant_commission';

    /**
     * @var array<string, int>
     */
    protected array $merchantCommissionIdsIndexedByKey = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $merchantCommissionGroupKey */
        $merchantCommissionGroupKey = $dataSet[MerchantCommissionStoreDataSetInterface::COLUMN_MERCHANT_COMMISSION_KEY];
        if (!isset($this->merchantCommissionGroupIdsIndexedByKey[$merchantCommissionGroupKey])) {
            $this->merchantCommissionIdsIndexedByKey[$merchantCommissionGroupKey] = $this->getIdMerchantCommissionByKey($merchantCommissionGroupKey);
        }

        $dataSet[static::ID_MERCHANT_COMMISSION] = $this->merchantCommissionIdsIndexedByKey[$merchantCommissionGroupKey];
    }

    /**
     * @param string $merchantCommissionKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdMerchantCommissionByKey(string $merchantCommissionKey): int
    {
        /** @var int $idMerchantCommission */
        $idMerchantCommission = $this->getMerchantCommissionQuery()
            ->select([static::COL_ID_MERCHANT_COMMISSION])
            ->findOneByKey($merchantCommissionKey);

        if (!$idMerchantCommission) {
            throw new EntityNotFoundException(
                sprintf('Could not find Merchant Commission by the key "%s"', $merchantCommissionKey),
            );
        }

        return $idMerchantCommission;
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    protected function getMerchantCommissionQuery(): SpyMerchantCommissionQuery
    {
        return SpyMerchantCommissionQuery::create();
    }
}
