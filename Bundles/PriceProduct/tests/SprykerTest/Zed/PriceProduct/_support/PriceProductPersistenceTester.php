<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct;

use Codeception\Actor;
use Generated\Shared\Transfer\PriceProductTransfer;

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
class PriceProductPersistenceTester extends Actor
{
    use _generated\PriceProductPersistenceTesterActions;

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductForProductConcrete(): PriceProductTransfer
    {
        $productConcrete = $this->haveProduct();
        $priceProductTransfer = $this->havePriceProduct([
            PriceProductTransfer::ID_PRODUCT => $productConcrete->getIdProductConcrete(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcrete->getAbstractSku(),
        ]);
        $priceProductTransfer->setSkuProductAbstract($productConcrete->getSku());

        return $priceProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceProductForProductAbstract(): PriceProductTransfer
    {
        $productAbstract = $this->haveProductAbstract();
        $priceProductTransfer = $this->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstract->getSku()]);
        $priceProductTransfer->setSkuProductAbstract($productAbstract->getSku());

        return $priceProductTransfer;
    }
}
