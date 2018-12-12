<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MultiCartDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\RelationMap;

class MultiCartDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $this->cleanTableRelations($this->getQuoteQuery());
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
            if ($relationType === RelationMap::ONE_TO_MANY && !in_array($fullyQualifiedQueryModel, $processedEntities)) {
                $processedEntities[] = $fullyQualifiedQueryModel;
                $fullyQualifiedQueryModelObject = $fullyQualifiedQueryModel::create();
                $this->cleanTableRelations($fullyQualifiedQueryModelObject, $processedEntities);
            }
        }

        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTablesContainsData(): void
    {
        $quoteQuery = $this->getQuoteQuery();
        $this->assertTrue($quoteQuery->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    protected function getQuoteQuery(): SpyQuoteQuery
    {
        return SpyQuoteQuery::create();
    }
}
