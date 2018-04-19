<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Persistence;

use Generated\Shared\Transfer\ReclamationTransfer;

/**
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationPersistenceFactory getFactory()
 */
interface SalesReclamationEntityManagerInterface
{
    /**
     * @see \Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationTableMap::COL_STATE_OPEN
     */
    public const RECLAMATION_STATE_OPEN = 'open';

    /**
     * @see \Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationItemTableMap::COL_STATE_OPEN
     */
    public const RECLAMATION_ITEM_STATE_OPEN = 'open';

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function saveReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer;
}
