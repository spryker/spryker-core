<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductOfferValidityDataImport;

use Codeception\Actor;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidityQuery;

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
class ProductOfferValidityDataImportCommunicationTester extends Actor
{
    use _generated\ProductOfferValidityDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureProductOfferValidityTableIsEmpty(): void
    {
        $query = $this->getProductOfferValidityQuery();
        $this->ensureDatabaseTableIsEmpty($query);
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertProductOfferValidityDatabaseTablesContainsData(): void
    {
        $configurableBundleTemplateQuery = $this->getProductOfferValidityQuery();

        $this->assertTrue(
            $configurableBundleTemplateQuery->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidityQuery
     */
    protected function getProductOfferValidityQuery(): SpyProductOfferValidityQuery
    {
        return SpyProductOfferValidityQuery::create();
    }
}
