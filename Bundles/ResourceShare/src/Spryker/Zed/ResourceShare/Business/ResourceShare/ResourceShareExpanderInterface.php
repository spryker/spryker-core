<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;

interface ResourceShareExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function executeResourceDataExpanderStrategyPlugins(
        ResourceShareResponseTransfer $resourceShareResponseTransfer
    ): ResourceShareResponseTransfer;
}
