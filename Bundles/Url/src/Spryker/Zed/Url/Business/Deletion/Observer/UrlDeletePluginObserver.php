<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Deletion\Observer;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Deletion\UrlDeleterAfterDeleteObserverInterface;
use Spryker\Zed\Url\Business\Deletion\UrlDeleterBeforeDeleteObserverInterface;

class UrlDeletePluginObserver implements UrlDeleterBeforeDeleteObserverInterface, UrlDeleterAfterDeleteObserverInterface
{

    /**
     * @var \Spryker\Zed\Url\Dependency\Plugin\UrlDeletePluginInterface[]
     */
    protected $urlDeletePlugins;

    /**
     * @param \Spryker\Zed\Url\Dependency\Plugin\UrlDeletePluginInterface[] $urlDeletePlugins
     */
    public function __construct(array $urlDeletePlugins)
    {
        $this->urlDeletePlugins = $urlDeletePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function handleUrlDeletion(UrlTransfer $urlTransfer)
    {
        foreach ($this->urlDeletePlugins as $urlCreatePlugin) {
            $urlCreatePlugin->delete($urlTransfer);
        }
    }

}
