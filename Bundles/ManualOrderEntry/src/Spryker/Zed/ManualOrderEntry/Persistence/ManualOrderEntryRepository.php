<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Persistence;

use Generated\Shared\Transfer\OrderSourceTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ManualOrderEntry\Business\Exception\OrderSourceNotFoundException;

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
     * @throws \Spryker\Zed\ManualOrderEntry\Business\Exception\OrderSourceNotFoundException
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer
     */
    public function getOrderSourceById($idOrderSource): OrderSourceTransfer
    {
        $query = $this->getFactory()->createOrderSourceQuery();
        $orderSourceEntity = $query->filterByIdOrderSource($idOrderSource)
            ->findOne();

        if (!$orderSourceEntity) {
            throw new OrderSourceNotFoundException();
        }

        return $this->getFactory()
            ->createOrderSourceMapper()
            ->mapOrderSourceEntityToTransfer($orderSourceEntity);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer[]
     */
    public function findAllOrderSources(): array
    {
        $orderSourceEntities = $this->getFactory()
            ->createOrderSourceQuery()
            ->find();

        $orderSourceTransfers = [];
        $mapper = $this->getFactory()->createOrderSourceMapper();

        foreach ($orderSourceEntities as $orderSourceEntity) {
            $orderSourceTransfer = $mapper->mapOrderSourceEntityToTransfer($orderSourceEntity);

            $orderSourceTransfers[] = $orderSourceTransfer;
        }

        return $orderSourceTransfers;
    }
}
