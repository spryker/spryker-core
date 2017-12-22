<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Communication\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Dependency\Plugin\CategoryRelationUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryNavigationConnector\Business\CategoryNavigationConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryNavigationConnector\Communication\CategoryNavigationConnectorCommunicationFactory getFactory()
 */
class UpdateNavigationRelationPlugin extends AbstractPlugin implements CategoryRelationUpdatePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this->getFacade()->updateCategoryNavigationNodesIsActive($categoryTransfer);
    }
}
