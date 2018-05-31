<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step;

use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet\PriceProductMerchantRelationshipDataSet;

class MerchantRelationshipKeyToIdMerchantRelationshipStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idMerchantRelationshipCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantRelationshipKey = $dataSet[PriceProductMerchantRelationshipDataSet::MERCHANT_RELATIONSHIP_KEY];
        if (!isset($this->idMerchantRelationshipCache[$merchantRelationshipKey])) {
            $idMerchantRelationship = SpyMerchantRelationshipQuery::create()
                ->select(SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP)
                ->findOneByMerchantRelationshipKey($merchantRelationshipKey);

            if (!$idMerchantRelationship) {
                throw new EntityNotFoundException(sprintf('Could not find Business Unit by key "%s"', $merchantRelationshipKey));
            }

            $this->idMerchantRelationshipCache[$merchantRelationshipKey] = $idMerchantRelationship;
        }

        $dataSet[PriceProductMerchantRelationshipDataSet::ID_MERCHANT_RELATIONSHIP] = $this->idMerchantRelationshipCache[$merchantRelationshipKey];
    }
}
