<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface;

class CategoryStoreRelationUpdater implements CategoryStoreRelationUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface
     */
    protected $categoryStoreAssignerPlugin;

    /**
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface $categoryStoreAssignerPlugin
     */
    public function __construct(CategoryStoreAssignerPluginInterface $categoryStoreAssignerPlugin)
    {
        $this->categoryStoreAssignerPlugin = $categoryStoreAssignerPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
     *
     * @return void
     */
    public function updateCategoryStoreRelation(UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer): void
    {
        $this->categoryStoreAssignerPlugin->handleStoreRelationUpdate($updateCategoryStoreRelationRequestTransfer);
    }
}
