<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentMethodStoreRelationReader implements ShipmentMethodStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $repository
     */
    public function __construct(ShipmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelationTransfer): StoreRelationTransfer
    {
        $storeRelationTransfer->requireIdEntity();

        $relatedStores = $this->repository->getReladedStoresByIdShipmentMethod(
            $storeRelationTransfer->getIdEntity()
        );

        $idStores = $this->getIdStores($relatedStores);

        $storeRelationTransfer
            ->setStores($relatedStores)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $relatedStores
     *
     * @return int[]
     */
    protected function getIdStores(ArrayObject $relatedStores): array
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $relatedStores->getArrayCopy());
    }
}
