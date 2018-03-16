<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;

class ChangeRequestExtendPluginExecutor implements ChangeRequestExtendPluginExecutorInterface
{
    /**
     * @var array|\Spryker\Client\PersistentCart\Dependency\Plugin\ChangeRequestExtendPluginInterface[]
     */
    protected $changeRequestExtendPlugins;

    /**
     * @param \Spryker\Client\PersistentCart\Dependency\Plugin\ChangeRequestExtendPluginInterface[] $changeRequestExtendPlugins
     */
    public function __construct(array $changeRequestExtendPlugins)
    {
        $this->changeRequestExtendPlugins = $changeRequestExtendPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function executePlugins(PersistentCartChangeTransfer $cartChangeTransfer): PersistentCartChangeTransfer
    {
        foreach ($this->changeRequestExtendPlugins as $changeRequestExtendPlugin) {
            $cartChangeTransfer = $changeRequestExtendPlugin->extend($cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }
}
