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
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function executePlugins(PersistentCartChangeTransfer $cartChangeTransfer): PersistentCartChangeTransfer;
}
