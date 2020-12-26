<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\PluginExecutor;

use Generated\Shared\Transfer\CategoryTransfer;

class CategoryPluginExecutor implements CategoryPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface[]
     */
    protected $categoryPostCreatePlugins;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUpdateAfterPluginInterface[]
     */
    protected $categoryPostUpdatePlugins;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryTransferExpanderPluginInterface[]
     */
    protected $categoryPostReadPlugins;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface[]
     */
    protected $categoryRelationUpdatePlugins;

    /**
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface[] $categoryPostCreatePlugins
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUpdateAfterPluginInterface[] $categoryPostUpdatePlugins
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryTransferExpanderPluginInterface[] $categoryPostReadPlugins
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface[] $categoryRelationUpdatePlugins
     */
    public function __construct(
        array $categoryPostCreatePlugins = [],
        array $categoryPostUpdatePlugins = [],
        array $categoryPostReadPlugins = [],
        array $categoryRelationUpdatePlugins = []
    ) {
        $this->categoryPostCreatePlugins = $categoryPostCreatePlugins;
        $this->categoryPostUpdatePlugins = $categoryPostUpdatePlugins;
        $this->categoryPostReadPlugins = $categoryPostReadPlugins;
        $this->categoryRelationUpdatePlugins = $categoryRelationUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function executePostUpdatePlugins(CategoryTransfer $categoryTransfer): void
    {
        foreach ($this->categoryPostUpdatePlugins as $plugin) {
            $plugin->execute($categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function executePostCreatePlugins(CategoryTransfer $categoryTransfer): void
    {
        foreach ($this->categoryPostCreatePlugins as $plugin) {
            $plugin->execute($categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function executePostReadPlugins(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        foreach ($this->categoryPostReadPlugins as $plugin) {
            $categoryTransfer = $plugin->expandCategory($categoryTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function executeCategoryRelationUpdatePlugins(CategoryTransfer $categoryTransfer): void
    {
        foreach ($this->categoryRelationUpdatePlugins as $categoryRelationUpdatePlugin) {
            $categoryRelationUpdatePlugin->update($categoryTransfer);
        }
    }
}
