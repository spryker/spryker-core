<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProduct;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;

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
 * @method \Spryker\Zed\MerchantProduct\Business\MerchantProductFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantProductBusinessTester extends Actor
{
    use _generated\MerchantProductBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantProductAbstractTableIsEmpty(): void
    {
        $merchantProductAbstractQuery = $this->getMerchantProductAbstractPropelQuery();
        $merchantProductAbstractQuery->deleteAll();
    }

    /**
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function getMerchantProductAbstractPropelQuery(): SpyMerchantProductAbstractQuery
    {
        return SpyMerchantProductAbstractQuery::create();
    }

    /**
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById(int $productAbstractId): ?ProductAbstractTransfer
    {
        $productAbstractEntity = SpyProductAbstractQuery::create()->findOneByIdProductAbstract($productAbstractId);

        if (!$productAbstractEntity) {
            return null;
        }

        $productAbstractTransfer = (new ProductAbstractTransfer())->fromArray($productAbstractEntity->toArray(), true);

        return $productAbstractTransfer;
    }
}
