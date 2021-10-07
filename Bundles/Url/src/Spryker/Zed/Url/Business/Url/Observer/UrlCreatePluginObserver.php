<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Url\Observer;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Url\UrlCreatorAfterSaveObserverInterface;
use Spryker\Zed\Url\Business\Url\UrlCreatorBeforeSaveObserverInterface;

class UrlCreatePluginObserver implements UrlCreatorBeforeSaveObserverInterface, UrlCreatorAfterSaveObserverInterface
{
    /**
     * @var array<\Spryker\Zed\Url\Dependency\Plugin\UrlCreatePluginInterface>
     */
    protected $urlCreatePlugins;

    /**
     * @param array<\Spryker\Zed\Url\Dependency\Plugin\UrlCreatePluginInterface> $urlCreatePlugins
     */
    public function __construct(array $urlCreatePlugins)
    {
        $this->urlCreatePlugins = $urlCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function handleUrlCreation(UrlTransfer $urlTransfer)
    {
        foreach ($this->urlCreatePlugins as $urlCreatePlugin) {
            $urlCreatePlugin->create($urlTransfer);
        }
    }
}
