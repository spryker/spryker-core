<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock;

use Codeception\Actor;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepository;
use Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface;

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
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferStockBusinessTester extends Actor
{
    use _generated\ProductOfferStockBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureProductOfferStockTableIsEmpty(): void
    {
        $query = $this->getProductOfferStockQuery();
        $this->ensureDatabaseTableIsEmpty($query);
        $query->deleteAll();
    }

    /**
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery
     */
    public function getProductOfferStockQuery(): SpyProductOfferStockQuery
    {
        return SpyProductOfferStockQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface
     */
    public function getProductOfferStockRepository(): ProductOfferStockRepositoryInterface
    {
        return new ProductOfferStockRepository();
    }
}
