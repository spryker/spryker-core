<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * @deprecated Use \Spryker\Zed\Sales\Business\Address\OrderAddressWriter class instead.
 */
class OrderAddressUpdater implements OrderAddressUpdaterInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param int $idAddress
     *
     * @return bool
     */
    public function update(AddressTransfer $addressTransfer, $idAddress)
    {
        $addressEntity = $this->queryContainer
            ->querySalesOrderAddressById($idAddress)
            ->findOne();

        if (empty($addressEntity)) {
            return false;
        }

        $this->hydrateAddressEntityFromTransfer($addressTransfer, $addressEntity);

        $addressEntity->save();

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $addressEntity
     *
     * @return void
     */
    protected function hydrateAddressEntityFromTransfer(
        AddressTransfer $addressTransfer,
        SpySalesOrderAddress $addressEntity
    ) {
        $addressEntity->fromArray($addressTransfer->modifiedToArray());
    }
}
