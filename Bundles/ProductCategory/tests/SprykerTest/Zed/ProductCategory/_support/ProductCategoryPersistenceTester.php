<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory;

use Codeception\Actor;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;

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
class ProductCategoryPersistenceTester extends Actor
{
    use _generated\ProductCategoryPersistenceTesterActions;

    protected const ATTRIBUTES = '[]';

    /**
     * @param string $name
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function createProductAbstractEntity(string $name, SpyLocale $localeEntity): SpyProductAbstract
    {
        $productAbstractLocalizedAttributesEntity = new SpyProductAbstractLocalizedAttributes();
        $productAbstractLocalizedAttributesEntity
            ->setName($name)
            ->setAttributes(static::ATTRIBUTES)
            ->setLocale($localeEntity);

        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity
            ->setSku($name)
            ->setAttributes(static::ATTRIBUTES)
            ->addSpyProductAbstractLocalizedAttributes($productAbstractLocalizedAttributesEntity)
            ->save();

        return $productAbstractEntity;
    }

    /**
     * @param string $localeName
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocale
     */
    public function createLocaleEntity(string $localeName): SpyLocale
    {
        $localeEntity = SpyLocaleQuery::create()
            ->filterByLocaleName($localeName)
            ->findOneOrCreate();

        $localeEntity->save();

        return $localeEntity;
    }
}
