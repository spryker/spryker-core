<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business\ResourceShare;

use Generated\Shared\Transfer\ResourceShareCriteriaTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;

interface ResourceShareReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareCriteriaTransfer $resourceShareCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShare(ResourceShareCriteriaTransfer $resourceShareCriteriaTransfer): ResourceShareResponseTransfer;
}
