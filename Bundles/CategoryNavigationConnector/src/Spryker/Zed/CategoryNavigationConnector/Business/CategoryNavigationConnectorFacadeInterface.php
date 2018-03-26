<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Business;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryNavigationConnectorFacadeInterface
{
    /**
     * Specification:
     * - Perform update on isActive flag of navigation nodes when category gets updated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryNavigationNodesIsActive(CategoryTransfer $categoryTransfer);
}
