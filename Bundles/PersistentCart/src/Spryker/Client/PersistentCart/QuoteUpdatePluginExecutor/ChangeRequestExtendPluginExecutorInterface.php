<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;

interface ChangeRequestExtendPluginExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function executePlugins(PersistentCartChangeTransfer $persistentCartChangeTransfer, array $params = []): PersistentCartChangeTransfer;
}
