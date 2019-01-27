<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory;

use Codeception\Actor;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface;

/**
 * Inherited Methods
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
class ProductCategoryBusinessTester extends Actor
{
    use _generated\ProductCategoryBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param int $idCategory
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function assignProductToCategory($idCategory, $idProductAbstract)
    {
        $spyProductCategory = new SpyProductCategory();
        $spyProductCategory
            ->setFkCategory($idCategory)
            ->setFkProductAbstract($idProductAbstract)
            ->save();
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    public function getProductCategoryFacade(): ProductCategoryFacadeInterface
    {
        /** @var \Spryker\Zed\ProductCategory\Business\ProductCategoryFacade $facade */
        $facade = $this->getFacade();

        return $facade;
    }
}
