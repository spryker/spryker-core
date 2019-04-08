<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Persistence;

use Generated\Shared\Transfer\ResourceShareCriteriaTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

interface ResourceShareRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareCriteriaTransfer $resourceShareCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    public function findResourceShareByCriteria(ResourceShareCriteriaTransfer $resourceShareCriteriaTransfer): ?ResourceShareTransfer;
}
