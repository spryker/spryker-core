<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence\Plugins;

use Generated\Shared\Transfer\BlogTransfer;

class BlogPluginExecutor implements BlogPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\Blog\Dependency\Plugin\PreSaveBlogPluginInterface[]
     */
    protected $blogPreSavePlugins = [];

    /**
     * @var \Spryker\Zed\Blog\Dependency\Plugin\PostSaveBlogPluginInterface[]
     */
    protected $blogPostSavePlugins = [];

    /**
     * @param \Spryker\Zed\Blog\Dependency\Plugin\PreSaveBlogPluginInterface[] $blogPreSavePlugins
     * @param \Spryker\Zed\Blog\Dependency\Plugin\PostSaveBlogPluginInterface[] $blogPostSavePlugins
     */
    public function __construct(array $blogPreSavePlugins, array $blogPostSavePlugins)
    {
        $this->blogPreSavePlugins = $blogPreSavePlugins;
        $this->blogPostSavePlugins = $blogPostSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function executeBlogPreSavePlugins(BlogTransfer $blogTransfer)
    {
        foreach ($this->blogPreSavePlugins as $blogPreSavePlugin) {
            $blogTransfer = $blogPreSavePlugin->preSave($blogTransfer);
        }

        return $blogTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function executeBlogPostSavePlugins(BlogTransfer $blogTransfer)
    {
        foreach ($this->blogPostSavePlugins as $blogPostSavePlugin) {
            $blogTransfer = $blogPostSavePlugin->postSave($blogTransfer);
        }

        return $blogTransfer;
    }

}
