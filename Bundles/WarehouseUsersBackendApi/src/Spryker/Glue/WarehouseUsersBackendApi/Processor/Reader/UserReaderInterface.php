<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\UserTransfer;

interface UserReaderInterface
{
    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUserTransferById(int $idUser): ?UserTransfer;
}
