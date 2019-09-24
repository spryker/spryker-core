<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage;

use Codeception\Actor;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductImageBusinessTester extends Actor
{
    use _generated\ProductImageBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param string $name
     * @param int|null $fkProductAbstract
     * @param int|null $fkProduct
     * @param int|null $fkLocale
     *
     * @return void
     */
    public function createProductImageSet(string $name, ?int $fkProductAbstract, ?int $fkProduct, ?int $fkLocale): void
    {
        $imageSetConcrete = new SpyProductImageSet();
        $imageSetConcrete
           ->setName($name)
           ->setFkProductAbstract($fkProductAbstract)
           ->setFkProduct($fkProduct)
           ->setFkLocale($fkLocale)
           ->save();
    }
}
