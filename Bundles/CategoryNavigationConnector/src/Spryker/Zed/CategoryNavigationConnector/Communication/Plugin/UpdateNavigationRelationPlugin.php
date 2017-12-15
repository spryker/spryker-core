<?php

namespace Spryker\Zed\CategoryNavigationConnector\Communication\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Dependency\Plugin\CategoryRelationUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryNavigationConnector\Business\CategoryNavigationConnectorFacadeInterface getFacade()
 */
class UpdateNavigationRelationPlugin extends AbstractPlugin implements CategoryRelationUpdatePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer $categoryTransfer
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this->getFacade()->updateCategoryNavigationNodesIsActive($categoryTransfer);
    }
}
