<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url\Observer;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface;
use Spryker\Zed\Url\Business\Url\UrlUpdaterBeforeSaveObserverInterface;

class UrlUpdatePluginObserver implements UrlUpdaterBeforeSaveObserverInterface, UrlUpdaterAfterSaveObserverInterface
{
    /**
     * @var array<\Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface>
     */
    protected $urlUpdatePlugins;

    /**
     * @param array<\Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface> $urlUpdatePlugins
     */
    public function __construct(array $urlUpdatePlugins)
    {
        $this->urlUpdatePlugins = $urlUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $originalUrlTransfer
     *
     * @return void
     */
    public function handleUrlUpdate(UrlTransfer $urlTransfer, UrlTransfer $originalUrlTransfer)
    {
        foreach ($this->urlUpdatePlugins as $urlUpdatePlugin) {
            $urlUpdatePlugin->update($urlTransfer);
        }
    }
}
