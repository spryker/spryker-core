<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;

interface ShareCartRequestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer|null
     */
    public function buildShareCartRequestTransfer(ResourceShareRequestTransfer $resourceShareRequestTransfer): ?ShareCartRequestTransfer;
}
