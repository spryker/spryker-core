<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Blog\Persistence\Plugins;

use Generated\Shared\Transfer\BlogTransfer;

interface BlogPluginExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function executeBlogPreSavePlugins(BlogTransfer $blogTransfer);

    /**
     * @param \Generated\Shared\Transfer\BlogTransfer $blogTransfer
     *
     * @return \Generated\Shared\Transfer\BlogTransfer
     */
    public function executeBlogPostSavePlugins(BlogTransfer $blogTransfer);
}
