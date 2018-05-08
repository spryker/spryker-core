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
        $query = $this->getFactory()->createOrderSourceQuery()
            ->filterByIdOrderSource($idOrderSource);
        $spyOrderSourceEntityTransfer = $this->buildQueryFromCriteria($query)
            ->findOne();

        if (!$spyOrderSourceEntityTransfer) {
            throw new OrderSourceNotFoundException();
        }
        $orderSourceTransfer = new OrderSourceTransfer();
        $orderSourceTransfer->fromArray($spyOrderSourceEntityTransfer->toArray(), true);

        return $orderSourceTransfer;
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer[]
     */
    public function findAllOrderSources(): array
    {
        $query = $this->getFactory()->createOrderSourceQuery();
        $spyOrderSourceEntityTransfers = $this->buildQueryFromCriteria($query)->find();
        $orderSourceTransfers = [];

        foreach ($spyOrderSourceEntityTransfers as $spyOrderSourceEntityTransfer) {
            $orderSourceTransfer = new OrderSourceTransfer();
            $orderSourceTransfer->fromArray($spyOrderSourceEntityTransfer->toArray(), true);

            $orderSourceTransfers[] = $orderSourceTransfer;
        }

        return $orderSourceTransfers;
    }
}
