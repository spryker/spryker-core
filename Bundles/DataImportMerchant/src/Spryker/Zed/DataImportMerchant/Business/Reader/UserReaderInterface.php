<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Reader;

use Generated\Shared\Transfer\UserCollectionTransfer;

interface UserReaderInterface
{
    /**
     * @param list<int> $userIds
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollectionByUserIds(array $userIds): UserCollectionTransfer;
}
