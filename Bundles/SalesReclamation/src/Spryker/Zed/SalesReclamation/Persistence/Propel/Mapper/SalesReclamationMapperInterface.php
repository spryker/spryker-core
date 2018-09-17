<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem;
use Propel\Runtime\Collection\ObjectCollection;

interface SalesReclamationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation
     */
    public function mapReclamationTransferToEntity(ReclamationTransfer $reclamationTransfer): SpySalesReclamation;

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $reclamationEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapEntityToReclamationTransfer(SpySalesReclamation $reclamationEntityTransfer): ReclamationTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem
     */
    public function mapReclamationItemTransferToEntity(ReclamationItemTransfer $reclamationItemTransfer): SpySalesReclamationItem;

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem $reclamationItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function mapEntityToReclamationItemTransfer(SpySalesReclamationItem $reclamationItemEntityTransfer): ReclamationItemTransfer;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapEntityToOrderTransfer(SpySalesOrder $orderEntityTransfer): OrderTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $spySalesOrders
     *
     * @return \Generated\Shared\Transfer\OrderCollectionTransfer
     */
    public function mapSalesOrdersToOrderCollectionTransfer(ObjectCollection $spySalesOrders): OrderCollectionTransfer;
}
