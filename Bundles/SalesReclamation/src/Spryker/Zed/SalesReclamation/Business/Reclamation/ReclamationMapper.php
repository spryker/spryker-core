<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;

class ReclamationMapper implements ReclamationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapOrderTransferToReclamationTransfer(
        OrderTransfer $orderTransfer,
        ReclamationTransfer $reclamationTransfer
    ): ReclamationTransfer {
        $orderTransfer->requireItems();

        $reclamationTransfer->setOrder($orderTransfer);
        $reclamationTransfer->setCustomerName($this->getCustomerNameFromOrder($orderTransfer));
        $reclamationTransfer->setCustomerReference($orderTransfer->getCustomerReference());
        $reclamationTransfer->setCustomerEmail($orderTransfer->getEmail());

        /** @var \Generated\Shared\Transfer\ReclamationItemTransfer[]|\ArrayObject $reclamationItems */
        $reclamationItems = new ArrayObject();
        $orderItems = $this->mapOrderItemsToReclamationItems($orderTransfer->getItems(), $reclamationItems);
        $reclamationTransfer->setReclamationItems($orderItems);

        return $reclamationTransfer;
    }

    /**
     * @param \ArrayObject $orderItems
     * @param \ArrayObject $reclamationItems
     *
     * @return \ArrayObject
     */
    protected function mapOrderItemsToReclamationItems(
        ArrayObject $orderItems,
        ArrayObject $reclamationItems
    ): ArrayObject {
        foreach ($orderItems as $itemTransfer) {
            $reclamationItemTransfer = new ReclamationItemTransfer();
            $reclamationItemTransfer->setOrderItem($itemTransfer);
            $reclamationItems->append($reclamationItemTransfer);
        }

        return $reclamationItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getCustomerNameFromOrder(OrderTransfer $orderTransfer): string
    {
        $salutation = $orderTransfer->getSalutation();

        return sprintf(
            '%s%s %s',
            $salutation ? $salutation . ' ' : '',
            $orderTransfer->getFirstName(),
            $orderTransfer->getLastName()
        );
    }
}
