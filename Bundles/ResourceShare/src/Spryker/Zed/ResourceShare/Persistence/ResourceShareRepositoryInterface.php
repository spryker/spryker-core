<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Persistence;

use Generated\Shared\Transfer\ResourceShareTransfer;

interface ResourceShareRepositoryInterface
{
    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    public function findResourceShareByUuid(string $uuid): ?ResourceShareTransfer;

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer|null
     */
    public function findExistingResourceShareForProvidedCustomer(ResourceShareTransfer $resourceShareTransfer): ?ResourceShareTransfer;
}
