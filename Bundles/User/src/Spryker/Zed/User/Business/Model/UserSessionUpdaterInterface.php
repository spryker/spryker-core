<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\Model;

interface UserSessionUpdaterInterface
{
    /**
     * @return bool
     */
    public function updateTtl(): bool;
}
