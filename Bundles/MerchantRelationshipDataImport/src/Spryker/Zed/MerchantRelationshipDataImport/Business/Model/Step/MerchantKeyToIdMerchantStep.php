<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipDataImport\Business\Model\Step;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantRelationshipDataImport\Business\Model\DataSet\MerchantRelationshipDataSetInterface;

class MerchantKeyToIdMerchantStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idMerchantCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantKey = $dataSet[MerchantRelationshipDataSetInterface::MERCHANT_KEY];
        if (!$merchantKey) {
            throw new InvalidDataException('"' . MerchantRelationshipDataSetInterface::MERCHANT_KEY . '" is required.');
        }

        if (!isset($this->idMerchantCache[$merchantKey])) {
            $idMerchant = SpyMerchantQuery::create()
                ->select(SpyMerchantTableMap::COL_ID_MERCHANT)
                ->findOneByMerchantKey($merchantKey);

            if (!$idMerchant) {
                throw new EntityNotFoundException(sprintf('Could not find Merchant by key "%s"', $merchantKey));
            }

            $this->idMerchantCache[$merchantKey] = $idMerchant;
        }

        $dataSet[MerchantRelationshipDataSetInterface::ID_MERCHANT] = $this->idMerchantCache[$merchantKey];
    }
}
