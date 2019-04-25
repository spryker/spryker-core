<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCartsRestApi\Zed;

use Generated\Shared\Transfer\ShareDetailCollectionTransfer;

interface SharedCartsRestApiStubInterface
{
    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid(string $uuid): ShareDetailCollectionTransfer;
}
