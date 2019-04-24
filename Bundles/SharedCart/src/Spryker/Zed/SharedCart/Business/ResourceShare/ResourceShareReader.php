<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\ResourceShareTransfer;

class ResourceShareReader implements ResourceShareReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isResourceShareActivatorStrategyApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return void
     */
    public function applyResourceShareActivatorStrategy(ResourceShareTransfer $resourceShareTransfer): void
    {
    }
}
