<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductOfferStockDataImport;

use Codeception\Actor;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferStockDataImportCommunicationTester extends Actor
{
    use _generated\ProductOfferStockDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureProductOfferStockTableIsEmpty(): void
    {
        $query = $this->getProductOfferQuery();
        $this->ensureDatabaseTableIsEmpty($query);
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertProductOfferStockDatabaseTablesContainsData(): void
    {
        $configurableBundleTemplateQuery = $this->getProductOfferStockQuery();

        $this->assertTrue(
            $configurableBundleTemplateQuery->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery
     */
    protected function getProductOfferStockQuery(): SpyProductOfferStockQuery
    {
        return SpyProductOfferStockQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
