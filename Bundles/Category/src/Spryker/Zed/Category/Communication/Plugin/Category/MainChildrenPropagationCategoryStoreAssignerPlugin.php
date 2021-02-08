<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Plugin\Category;

use Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 */
class MainChildrenPropagationCategoryStoreAssignerPlugin extends AbstractPlugin implements CategoryStoreAssignerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates category store relation for passed category.
     * - Updates category store relation for children category nodes where `category_node.is_main` is true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
     *
     * @return void
     */
    public function handleStoreRelationUpdate(UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer): void
    {
        $this->getFacade()->updateCategoryStoreRelationWithMainChildrenPropagation($updateCategoryStoreRelationRequestTransfer);
    }
}
