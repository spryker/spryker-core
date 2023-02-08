<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\UserCollectionTransfer;

interface UserReaderInterface
{
    /**
     * @param array<string> $userUuids
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollectionTransferByUuids(array $userUuids): UserCollectionTransfer;
}
