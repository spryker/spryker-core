<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CategoryNavigationConnector\Business\CategoryNavigationConnectorBusinessFactory getFactory()
 */
class CategoryNavigationConnectorFacade extends AbstractFacade implements CategoryNavigationConnectorFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryNavigationNodesIsActive(CategoryTransfer $categoryTransfer)
    {
        $this->getFactory()
            ->createNavigationNodesIsActiveUpdater()
            ->updateCategoryNodeNavigationNodes($categoryTransfer->getCategoryNode()->getIdCategoryNode(), $categoryTransfer->getIsActive());
    }
}
