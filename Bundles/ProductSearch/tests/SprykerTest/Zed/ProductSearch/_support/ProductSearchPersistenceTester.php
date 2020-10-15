<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch;

use Codeception\Actor;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute;

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
class ProductSearchPersistenceTester extends Actor
{
    use _generated\ProductSearchPersistenceTesterActions;

    /**
     * @param string $filterType
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute
     */
    public function createProductSearchAttribute(string $filterType): SpyProductSearchAttribute
    {
        $productAttributeKey = new SpyProductAttributeKey();
        $productAttributeKey->setKey("{$filterType}_key");
        $productAttributeKey->save();

        $productSearchAttribute = new SpyProductSearchAttribute();
        $productSearchAttribute->setFilterType($filterType);
        $productSearchAttribute->setFkProductAttributeKey($productAttributeKey->getIdProductAttributeKey());
        $productSearchAttribute->save();

        return $productSearchAttribute;
    }
}
