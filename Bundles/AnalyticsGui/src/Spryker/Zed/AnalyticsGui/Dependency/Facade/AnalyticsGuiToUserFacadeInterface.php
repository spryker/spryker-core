<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AnalyticsGui\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;

interface AnalyticsGuiToUserFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser(): UserTransfer;
}
