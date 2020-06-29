<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProduct;

use Codeception\Actor;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;

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
 * @method \Spryker\Zed\MerchantProduct\Business\MerchantProductFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantProductBusinessTester extends Actor
{
    use _generated\MerchantProductBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function saveMerchantProduct(MerchantTransfer $merchantTransfer, ProductConcreteTransfer $productConcreteTransfer): void
    {
        $merchantProductAbstract = $this->getMerchantProductAbstractPropelQuery()
            ->filterByFkMerchant($merchantTransfer->getIdMerchant())
            ->filterByFkProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->findOneOrCreate();

        $merchantProductAbstract->save();
    }

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
}
