<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
