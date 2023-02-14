<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Plugin\Category;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategory\ProductCategoryConfig getConfig()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface getQueryContainer()
 */
class ProductUpdateEventTriggerCategoryRelationUpdatePlugin extends AbstractPlugin implements CategoryRelationUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `Category.categoryNode.idCategoryNode` to be set.
     * - Triggers product update events for products that are assigned to the given category and its child categories.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer): void
    {
        $this->getFacade()->triggerProductUpdateEventsForCategory($categoryTransfer);
    }
}
