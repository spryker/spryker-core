<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Reader\User;

use Generated\Shared\Transfer\UserTransfer;

interface UserReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUser(): UserTransfer;
}
