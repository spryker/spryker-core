<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

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
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface[] $categoryPostCreatePlugins
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUpdateAfterPluginInterface[] $categoryPostUpdatePlugins
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryTransferExpanderPluginInterface[] $categoryPostReadPlugins
     */
    public function __construct(
        array $categoryPostCreatePlugins = [],
        array $categoryPostUpdatePlugins = [],
        array $categoryPostReadPlugins = []
    ) {
        $this->categoryPostCreatePlugins = $categoryPostCreatePlugins;
        $this->categoryPostUpdatePlugins = $categoryPostUpdatePlugins;
        $this->categoryPostReadPlugins = $categoryPostReadPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function executePostUpdatePlugins(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        foreach ($this->categoryPostUpdatePlugins as $plugin) {
            $categoryTransfer = $plugin->execute($categoryTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function executePostCreatePlugins(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        foreach ($this->categoryPostCreatePlugins as $plugin) {
            $categoryTransfer = $plugin->execute($categoryTransfer);
        }

        return $categoryTransfer;
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
}
