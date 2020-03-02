<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelDataImport;

use Codeception\Actor;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
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
    public function ensureProductLabelTableIsEmpty(): void
    {
        SpyProductLabelProductAbstractQuery::create()->deleteAll();
        SpyProductLabelLocalizedAttributesQuery::create()->deleteAll();
        SpyProductLabelStoreQuery::create()->deleteAll();
        SpyProductLabelQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureProductLabelStoreTableIsEmpty(): void
    {
        SpyProductLabelStoreQuery::create()->deleteAll();
    }
}
