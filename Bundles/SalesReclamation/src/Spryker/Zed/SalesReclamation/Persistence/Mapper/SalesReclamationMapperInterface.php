<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence\Mapper;

use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Generated\Shared\Transfer\SpySalesReclamationEntityTransfer;
use Generated\Shared\Transfer\SpySalesReclamationItemEntityTransfer;

interface SalesReclamationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesReclamationEntityTransfer
     */
    public function mapReclamationTransferToEntityTransfer(ReclamationTransfer $reclamationTransfer): SpySalesReclamationEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpySalesReclamationEntityTransfer $reclamationEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapEntityTransferToReclamationTransfer(SpySalesReclamationEntityTransfer $reclamationEntityTransfer): ReclamationTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesReclamationItemEntityTransfer
     */
    public function mapReclamationItemTransferToEntityTransfer(ReclamationItemTransfer $reclamationItemTransfer): SpySalesReclamationItemEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpySalesReclamationItemEntityTransfer $reclamationItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function mapEntityTransferToReclamationItemTransfer(SpySalesReclamationItemEntityTransfer $reclamationItemEntityTransfer): ReclamationItemTransfer;
}
