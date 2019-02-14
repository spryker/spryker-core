<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Dependency\Facade;

interface UserLocaleToUserFacadeBridgeInterface
{
    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

    /**
     * @return bool
     */
    public function hasCurrentUser();
}
