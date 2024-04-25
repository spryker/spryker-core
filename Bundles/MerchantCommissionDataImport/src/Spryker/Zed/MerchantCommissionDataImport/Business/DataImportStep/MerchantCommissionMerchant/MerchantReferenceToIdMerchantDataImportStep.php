<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionMerchant;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataSet\MerchantCommissionMerchantDataSetInterface;

class MerchantReferenceToIdMerchantDataImportStep implements DataImportStepInterface
{
    /**
     * @uses \Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap::COL_ID_MERCHANT
     *
     * @var string
     */
    protected const COL_ID_MERCHANT = 'spy_merchant.id_merchant';

    /**
     * @var array<string, int>
     */
    protected array $merchantIdsIndexedByMerchantReference = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $merchantReference */
        $merchantReference = $dataSet[MerchantCommissionMerchantDataSetInterface::COLUMN_MERCHANT_REFERENCE];
        if (!isset($this->merchantIdsIndexedByMerchantReference[$merchantReference])) {
            $this->merchantIdsIndexedByMerchantReference[$merchantReference] = $this->getIdMerchantByMerchantReference($merchantReference);
        }

        $dataSet[MerchantCommissionMerchantDataSetInterface::ID_MERCHANT] = $this->merchantIdsIndexedByMerchantReference[$merchantReference];
    }

    /**
     * @param string $merchantReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdMerchantByMerchantReference(string $merchantReference): int
    {
        /** @var int $idMerchant */
        $idMerchant = $this->getMerchantQuery()
            ->select(static::COL_ID_MERCHANT)
            ->findOneByMerchantReference($merchantReference);

        if (!$idMerchant) {
            throw new EntityNotFoundException(
                sprintf('Could not find Merchant by the reference "%s"', $merchantReference),
            );
        }

        return $idMerchant;
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }
}
