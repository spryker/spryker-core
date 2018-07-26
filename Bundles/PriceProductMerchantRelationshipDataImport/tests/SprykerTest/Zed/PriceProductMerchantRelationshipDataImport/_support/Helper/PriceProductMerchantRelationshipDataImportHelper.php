<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductMerchantRelationshipDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
use Propel\Runtime\Map\RelationMap;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PriceProductMerchantRelationshipDataImportHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $relations = $this->getMerchantRelationshipQuery()->getTableMap()->getRelations();

        $query = $this->getMerchantRelationshipQuery();
        $results = $query->find();
        // @todo rewrite this.
        foreach ($relations as $relationMap) {
            $relationType = $relationMap->getType();

            foreach ($results as $result) {
                if ($relationType == RelationMap::ONE_TO_MANY) {
                    $relationName = $relationMap->getPluralName();
                    $method = 'get' . $relationName;
                    $childRecords = $result->$method();

                    foreach ($childRecords as $childRecord) {
                        $childRecord->delete();
                    }
                }
            }
        }

        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $priceProductQuery = $this->getPriceProductMerchantRelationshipQuery();
        $this->assertFalse($priceProductQuery->exists(), 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $priceProductQuery = $this->getPriceProductMerchantRelationshipQuery();
        $this->assertTrue($priceProductQuery->exists(), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery
     */
    protected function getPriceProductMerchantRelationshipQuery(): SpyPriceProductMerchantRelationshipQuery
    {
        return SpyPriceProductMerchantRelationshipQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function getMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }
}
