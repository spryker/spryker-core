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
     * @var array|\Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface[]
     */
    protected $changeRequestExtendPlugins;

    /**
     * @param \Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface[] $changeRequestExtendPlugins
     */
    public function __construct(array $changeRequestExtendPlugins)
    {
        $this->changeRequestExtendPlugins = $changeRequestExtendPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function executePlugins(PersistentCartChangeTransfer $persistentCartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        foreach ($this->changeRequestExtendPlugins as $changeRequestExtendPlugin) {
            $persistentCartChangeTransfer = $changeRequestExtendPlugin->extend($persistentCartChangeTransfer, $params);
        }

        return $persistentCartChangeTransfer;
    }
}
