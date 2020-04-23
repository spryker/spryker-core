<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelDataImport;

use Codeception\Actor;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelStoreQuery;

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
class ProductLabelDataImportCommunicationTester extends Actor
{
    use _generated\ProductLabelDataImportCommunicationTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @return void
     */
    public function truncateProductLabelProductAbstractRelations(): void
    {
        $this->truncateTableRelations($this->createProductLabelProductAbstractQuery());
    }

    /**
     * @return void
     */
    public function truncateProductLabelStoreRelations(): void
    {
        $this->truncateTableRelations($this->createProductLabelStoreQuery());
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    protected function createProductLabelProductAbstractQuery(): SpyProductLabelProductAbstractQuery
    {
        return SpyProductLabelProductAbstractQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelStoreQuery
     */
    protected function createProductLabelStoreQuery(): SpyProductLabelStoreQuery
    {
        return SpyProductLabelStoreQuery::create();
    }
}
