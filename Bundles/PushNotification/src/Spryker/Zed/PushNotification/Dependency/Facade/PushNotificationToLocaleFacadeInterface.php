<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Dependency\Facade;

use Generated\Shared\Transfer\LocaleCriteriaTransfer;

interface PushNotificationToLocaleFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\LocaleCriteriaTransfer|null $localeCriteriaTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleCollection(?LocaleCriteriaTransfer $localeCriteriaTransfer = null): array;
}
