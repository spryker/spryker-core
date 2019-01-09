<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem;

interface SalesReclamationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $salesReclamationEntity
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation
     */
    public function mapReclamationTransferToEntity(
        ReclamationTransfer $reclamationTransfer,
        SpySalesReclamation $salesReclamationEntity
    ): SpySalesReclamation;

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation $reclamationEntity
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapReclamationEntityToTransfer(
        SpySalesReclamation $reclamationEntity,
        ReclamationTransfer $reclamationTransfer
    ): ReclamationTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem $salesReclamationItemEntity
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem
     */
    public function mapReclamationItemTransferToEntity(
        ReclamationItemTransfer $reclamationItemTransfer,
        SpySalesReclamationItem $salesReclamationItemEntity
    ): SpySalesReclamationItem;

    /**
     * @param \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem $reclamationItemEntity
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function mapReclamationItemEntityToTransfer(
        SpySalesReclamationItem $reclamationItemEntity,
        ReclamationItemTransfer $reclamationItemTransfer
    ): ReclamationItemTransfer;
}
