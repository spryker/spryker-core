<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductRelationTypeBuilder;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery;

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
class ProductRelationPersistenceTester extends Actor
{
    use _generated\ProductRelationPersistenceTesterActions;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer
     */
    public function haveProductRelationType(array $seedData = []): ProductRelationTypeTransfer
    {
        $productRelationTypeTransfer = (new ProductRelationTypeBuilder($seedData))->build();

        $productRelationTypeEntity = SpyProductRelationTypeQuery::create()
            ->filterByKey($productRelationTypeTransfer->getKey())
            ->findOneOrCreate();

        $productRelationTypeEntity->fromArray($productRelationTypeTransfer->toArray());
        $productRelationTypeEntity->save();

        $this->addCleanup(function () use ($productRelationTypeEntity): void {
            $this->removeProductRelationType($productRelationTypeEntity->getIdProductRelationType());
        });

        return $productRelationTypeTransfer->fromArray($productRelationTypeEntity->toArray(), true);
    }

    /**
     * @param int $idProductRelationType
     *
     * @return void
     */
    protected function removeProductRelationType(int $idProductRelationType): void
    {
        SpyProductRelationTypeQuery::create()
            ->filterByIdProductRelationType($idProductRelationType)
            ->delete();
    }
}
