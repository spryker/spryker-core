<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Deleter;

use Generated\Shared\Transfer\SalesReclamationItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesReclamationItemCollectionResponseTransfer;

interface SalesReclamationItemDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesReclamationItemCollectionDeleteCriteriaTransfer $salesReclamationItemCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesReclamationItemCollectionResponseTransfer
     */
    public function deleteSalesReclamationItemCollection(
        SalesReclamationItemCollectionDeleteCriteriaTransfer $salesReclamationItemCollectionDeleteCriteriaTransfer
    ): SalesReclamationItemCollectionResponseTransfer;
}
