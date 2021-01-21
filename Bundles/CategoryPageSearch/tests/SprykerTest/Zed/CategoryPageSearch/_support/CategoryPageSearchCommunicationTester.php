<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch;

use Codeception\Actor;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;

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
class CategoryPageSearchCommunicationTester extends Actor
{
    use _generated\CategoryPageSearchCommunicationTesterActions;

    public function getCategoryNodeTree(array $categoryNodeIds, int $idLocale): array
    {
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery $query */
        $query = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByIdCategoryNode_In($categoryNodeIds)
            ->joinWithSpyUrl()
            ->joinWithCategory()
            ->joinWith('Category.Attribute')
            ->joinWith('Category.CategoryTemplate')
            ->where(SpyCategoryAttributeTableMap::COL_FK_LOCALE . ' = ?', $idLocale)
            ->where(SpyUrlTableMap::COL_FK_LOCALE . ' = ?', $idLocale)
            ->where(SpyCategoryTableMap::COL_IS_ACTIVE . ' = ?', true)
            ->where(SpyCategoryTableMap::COL_IS_IN_MENU . ' = ?', true)
            ->orderByIdCategoryNode()
            ->find()
            ->getFirst();

        return $query;
    }
}
