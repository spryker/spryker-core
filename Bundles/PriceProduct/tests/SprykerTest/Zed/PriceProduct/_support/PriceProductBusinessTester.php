<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct;

use Codeception\Actor;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Propel\Runtime\Collection\ObjectCollection;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceProductBusinessTester extends Actor
{
    use _generated\PriceProductBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function havePriceProductStore(PriceProductTransfer $priceProductTransfer): string
    {
        $storeTransfer = $this->haveStore();
        $idCurrency = $this->haveCurrency();

        $priceProductStoreEntity = (new SpyPriceProductStore())
            ->setFkStore($storeTransfer->getIdStoreOrFail())
            ->setFkCurrency($idCurrency)
            ->setFkPriceProduct($priceProductTransfer->getIdPriceProductOrFail())
            ->setGrossPrice(100)
            ->setNetPrice(100);

        $priceProductStoreEntity->save();

        return $priceProductStoreEntity->getIdPriceProductStore();
    }

    /**
     * @param list<int> $idsPriceProductDefault
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault>
     */
    public function findPriceProductDefaults(array $idsPriceProductDefault): ObjectCollection
    {
        return $this->getPriceProductDefaultQuery()
            ->filterByIdPriceProductDefault_In($idsPriceProductDefault)
            ->find();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery
     */
    protected function getPriceProductDefaultQuery(): SpyPriceProductDefaultQuery
    {
        return SpyPriceProductDefaultQuery::create();
    }
}
