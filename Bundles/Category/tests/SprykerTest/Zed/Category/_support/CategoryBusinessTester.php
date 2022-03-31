<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;

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
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CategoryBusinessTester extends Actor
{
    use _generated\CategoryBusinessTesterActions;

    /**
     * @param int $idCategory
     *
     * @return int
     */
    public function getStoresCountByIdCategory(int $idCategory): int
    {
        return SpyCategoryStoreQuery::create()
            ->filterByFkCategory($idCategory)
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idCategory
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    public function createCategoryLocalizedAttributesForLocale(
        LocaleTransfer $localeTransfer,
        int $idCategory,
        array $seedData = []
    ): CategoryLocalizedAttributesTransfer {
        $categoryLocalizedAttributesData = (new CategoryLocalizedAttributesBuilder($seedData))->build()->toArray();
        $categoryLocalizedAttributesData[LocalizedAttributesTransfer::LOCALE] = $localeTransfer;

        return $this->haveCategoryLocalizedAttributeForCategory($idCategory, $categoryLocalizedAttributesData);
    }
}
