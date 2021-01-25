<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationStorage;

use Codeception\Actor;
use Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorageQuery;

/**
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
class ProductRelationStorageBusinessTester extends Actor
{
    use _generated\ProductRelationStorageBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureProductRelationStorageTableIsEmpty(): void
    {
        SpyProductAbstractRelationStorageQuery::create()->deleteAll();
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return bool
     */
    public function isProductAbstractRelationStorageRecordExists(int $idProductAbstract, string $storeName): bool
    {
        return SpyProductAbstractRelationStorageQuery::create()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByStore($storeName)
            ->exists();
    }
}
