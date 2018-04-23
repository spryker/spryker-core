<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Persistence;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryPersistenceFactory getFactory()
 */
class ManualOrderEntryRepository extends AbstractRepository implements ManualOrderEntryRepositoryInterface
{
    /**
     * @api
     *
     * @param int $idOrderSource
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer
     */
    public function getOrderSourceById($idOrderSource): OrderSourceTransfer
    {
        $query = $this->getFactory()->createOrderSourceQuery();
        $orderSourceEntity = $query->filterByIdOrderSource($idOrderSource)
            ->findOne();

        $orderSourceTransfer = new OrderSourceTransfer();
        if ($orderSourceEntity) {
            $orderSourceTransfer->setIdOrderSource($orderSourceEntity->getIdOrderSource());
            $orderSourceTransfer->setName($orderSourceEntity->getName());
        }

        return $orderSourceTransfer;
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer[]
     */
    public function getAllOrderSources(): array
    {
        $query = $this->getFactory()->createOrderSourceQuery();
        $orderSourceEntities = $query->find();
        $orderSourceTransfers = [];

        foreach ($orderSourceEntities as $orderSourceEntity) {
            $orderSourceTransfer = new OrderSourceTransfer();
            $orderSourceTransfer->fromArray($orderSourceEntity->toArray(), true);

            $orderSourceTransfers[] = $orderSourceTransfer;
        }

        return $orderSourceTransfers;
    }
}
