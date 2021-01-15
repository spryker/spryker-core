<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface;

class CategoryRelationshipUpdater implements CategoryRelationshipUpdaterInterface
{
    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryNodeUpdaterInterface
     */
    protected $categoryNodeUpdater;

    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryUrlUpdaterInterface
     */
    protected $categoryUrlUpdater;

    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryAttributeUpdaterInterface
     */
    protected $categoryAttributeUpdater;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface
     */
    protected $categoryStoreAssignerPlugin;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface[]
     */
    protected $categoryRelationUpdatePlugins;

    /**
     * @param \Spryker\Zed\Category\Business\Updater\CategoryNodeUpdaterInterface $categoryNodeUpdater
     * @param \Spryker\Zed\Category\Business\Updater\CategoryUrlUpdaterInterface $categoryUrlUpdater
     * @param \Spryker\Zed\Category\Business\Updater\CategoryAttributeUpdaterInterface $categoryAttributeUpdater
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface $categoryStoreAssignerPlugin
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface[] $categoryRelationUpdatePlugins
     */
    public function __construct(
        CategoryNodeUpdaterInterface $categoryNodeUpdater,
        CategoryUrlUpdaterInterface $categoryUrlUpdater,
        CategoryAttributeUpdaterInterface $categoryAttributeUpdater,
        CategoryStoreAssignerPluginInterface $categoryStoreAssignerPlugin,
        array $categoryRelationUpdatePlugins
    ) {
        $this->categoryNodeUpdater = $categoryNodeUpdater;
        $this->categoryUrlUpdater = $categoryUrlUpdater;
        $this->categoryAttributeUpdater = $categoryAttributeUpdater;
        $this->categoryStoreAssignerPlugin = $categoryStoreAssignerPlugin;
        $this->categoryRelationUpdatePlugins = $categoryRelationUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryRelationships(CategoryTransfer $categoryTransfer): void
    {
        $this->executeCategoryRelationUpdatePlugins($categoryTransfer);

        $this->categoryNodeUpdater->updateCategoryNode($categoryTransfer);
        $this->categoryAttributeUpdater->updateCategoryAttributes($categoryTransfer);
        $this->categoryUrlUpdater->updateCategoryUrl($categoryTransfer);
        $this->categoryNodeUpdater->updateExtraParentCategoryNodes($categoryTransfer);
        $this->categoryStoreAssignerPlugin->handleStoreRelationUpdate(
            $categoryTransfer->getIdCategoryOrFail(),
            $categoryTransfer->getStoreRelationOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCategoryRelationUpdatePlugins(CategoryTransfer $categoryTransfer): void
    {
        foreach ($this->categoryRelationUpdatePlugins as $categoryRelationUpdatePlugin) {
            $categoryRelationUpdatePlugin->update($categoryTransfer);
        }
    }
}
