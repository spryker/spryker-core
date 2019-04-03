<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\Uuid;

use Generated\Shared\Transfer\ResourceShareTransfer;

interface ResourceShareUuidGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return string
     */
    public function generateResourceShareUuid(ResourceShareTransfer $resourceShareTransfer): string;
}
