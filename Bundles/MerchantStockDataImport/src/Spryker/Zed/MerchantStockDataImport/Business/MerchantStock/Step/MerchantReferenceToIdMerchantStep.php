<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStockDataImport\Business\MerchantStock\Step;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantStockDataImport\Business\MerchantStock\DataSet\MerchantStockDataSetInterface;

class MerchantReferenceToIdMerchantStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idMerchantCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantReference = $dataSet[MerchantStockDataSetInterface::MERCHANT_REFERENCE];

        if (!$merchantReference) {
            throw new InvalidDataException(sprintf('"%s" is required.', MerchantStockDataSetInterface::MERCHANT_REFERENCE));
        }

        if (!isset($this->idMerchantCache[$merchantReference])) {
            $this->idMerchantCache[$merchantReference] = $this->getIdMerchant($merchantReference);
        }

        $dataSet[MerchantStockDataSetInterface::MERCHANT_ID] = $this->idMerchantCache[$merchantReference];
    }

    /**
     * @param string $merchantReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdMerchant(string $merchantReference): int
    {
        /** @var \Orm\Zed\Merchant\Persistence\SpyMerchantQuery $merchantQuery */
        $merchantQuery = SpyMerchantQuery::create()
            ->select(SpyMerchantTableMap::COL_ID_MERCHANT);
        /** @var int $idMerchant */
        $idMerchant = $merchantQuery->findOneByMerchantReference($merchantReference);

        if (!$idMerchant) {
            throw new EntityNotFoundException(sprintf('Could not find Merchant by reference "%s"', $merchantReference));
        }

        return $idMerchant;
    }
}
