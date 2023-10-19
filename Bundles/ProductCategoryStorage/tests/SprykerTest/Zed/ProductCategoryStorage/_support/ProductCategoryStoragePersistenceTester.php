<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\NodeBuilder;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;

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
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductCategoryStorage\PHPMD)
 */
class ProductCategoryStoragePersistenceTester extends Actor
{
    use _generated\ProductCategoryStoragePersistenceTesterActions;

    /**
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function getRootCategoryNode(): NodeTransfer
    {
        $categoryNodeEntity = SpyCategoryNodeQuery::create()
            ->filterByIsRoot(true)
            ->findOne();

        return (new NodeTransfer())->fromArray($categoryNodeEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $parentNodeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function haveLocalizedCategoryTransferWithStoreRelation(NodeTransfer $parentNodeTransfer, StoreTransfer $storeTransfer): CategoryTransfer
    {
        $categoryTransfer = $this->haveLocalizedCategory([
            CategoryTransfer::CATEGORY_NODE => (new NodeBuilder([
                NodeTransfer::FK_PARENT_CATEGORY_NODE => $parentNodeTransfer->getIdCategoryNode(),
            ]))->build()->toArray(),
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentNodeTransfer->toArray(),
        ]);
        $this->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        return $categoryTransfer;
    }
}
