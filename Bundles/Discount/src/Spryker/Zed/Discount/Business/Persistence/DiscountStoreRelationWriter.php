<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Discount\Business\Persistence;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountStore;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class DiscountStoreRelationWriter implements DiscountStoreRelationWriterInterface
{
    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationReaderInterface
     */
    protected $discountStoreRelationReader;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationReaderInterface $discountStoreRelationReader
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer, DiscountStoreRelationReaderInterface $discountStoreRelationReader)
    {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->discountStoreRelationReader = $discountStoreRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelation)
    {
        $storeRelation->requireIdEntity();

        $currentIdStores = $this->getIdStores($storeRelation->getIdEntity());
        $requestedIdStores = $this->findStoreRelationIdStores($storeRelation);

        $saveIdStores = array_diff($requestedIdStores, $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $requestedIdStores);

        $this->addStores($saveIdStores, $storeRelation->getIdEntity());
        $this->removeStores($deleteIdStores, $storeRelation->getIdEntity());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return int[]
     */
    protected function findStoreRelationIdStores(StoreRelationTransfer $storeRelation)
    {
        if ($storeRelation->getIdStores() === null) {
            return [];
        }

        return $storeRelation->getIdStores();
    }

    /**
     * @param int[] $idStores
     * @param int $idDiscount
     *
     * @return void
     */
    protected function addStores(array $idStores, $idDiscount)
    {
        foreach ($idStores as $idStore) {
            (new SpyDiscountStore())
                ->setFkStore($idStore)
                ->setFkDiscount($idDiscount)
                ->save();
        }
    }

    /**
     * @param int[] $idStores
     * @param int $idDiscount
     *
     * @return void
     */
    protected function removeStores(array $idStores, $idDiscount)
    {
        if (count($idStores) === 0) {
            return;
        }

        $this->discountQueryContainer
            ->queryDiscountStoreByFkDiscountAndFkStores($idDiscount, $idStores)
            ->delete();
    }

    /**
     * @param int $idDiscount
     *
     * @return int[]
     */
    protected function getIdStores($idDiscount)
    {
        $storeRelation = $this->discountStoreRelationReader->getStoreRelation(
            (new StoreRelationTransfer())
                ->setIdEntity($idDiscount)
        );

        return $storeRelation->getIdStores();
    }
}
