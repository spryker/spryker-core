<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\ProductCategory\ProductCategory\Zed\Tester;

use Acceptance\ProductCategory\ProductCategory\Zed\PageObject\ProductCategoryAssignPage;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use ProductCategory\ZedAcceptanceTester;

class ProductCategoryAssignTester extends ZedAcceptanceTester
{

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function createProductEntity($name)
    {
        $localeEntity = $this->createLocaleEntity('en_US');

        $productAbstractLocalizedAttributesEntity = new SpyProductAbstractLocalizedAttributes();
        $productAbstractLocalizedAttributesEntity
            ->setName($name)
            ->setAttributes('[]')
            ->setLocale($localeEntity);

        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity
            ->setSku($name)
            ->setAttributes('[]')
            ->addSpyProductAbstractLocalizedAttributes($productAbstractLocalizedAttributesEntity)
            ->save();

        return $productAbstractEntity;
    }

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
     * @param string $localeName
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocale
     */
    public function createLocaleEntity($localeName)
    {
        $localeEntity = SpyLocaleQuery::create()
            ->filterByLocaleName($localeName)
            ->findOneOrCreate();

        $localeEntity->save();

        return $localeEntity;
    }

    /**
     * @param string $productName
     *
     * @return void
     */
    public function searchTableByProductName($productName)
    {
        $this->fillField(ProductCategoryAssignPage::SELECTOR_TABLE_SEARCH, $productName);
        $this->wait(3);
    }

}
