<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\RelationMap;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchant(array $seedData = []): MerchantTransfer
    {
        $merchantTransfer = (new MerchantBuilder($seedData))->build();
        $merchantTransfer->setIdMerchant(null);

        return $this->getLocator()->merchant()->facade()->createMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $this->cleanTableRelations($this->getMerchantQuery());
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $processedEntities
     *
     * @return void
     */
    protected function cleanTableRelations(ModelCriteria $query, array $processedEntities = []): void
    {
        $relations = $query->getTableMap()->getRelations();

        foreach ($relations as $relationMap) {
            $relationType = $relationMap->getType();
            $fullyQualifiedQueryModel = $relationMap->getLocalTable()->getClassname() . 'Query';
            if ($relationType == RelationMap::ONE_TO_MANY && !in_array($fullyQualifiedQueryModel, $processedEntities)) {
                $processedEntities[] = $fullyQualifiedQueryModel;
                $fullyQualifiedQueryModelObject = $fullyQualifiedQueryModel::create();
                $this->cleanTableRelations($fullyQualifiedQueryModelObject, $processedEntities);
            }
        }

        $query->deleteAll();
    }

    /**
     * @param int $idMerchant
     *
     * @return void
     */
    public function assertMerchantNotExists(int $idMerchant): void
    {
        $query = $this->getMerchantQuery()->filterByIdMerchant($idMerchant);
        $this->assertSame(0, $query->count());
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }
}
