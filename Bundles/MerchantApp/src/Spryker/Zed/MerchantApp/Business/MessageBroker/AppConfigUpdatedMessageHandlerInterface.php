<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MessageBroker;

use Generated\Shared\Transfer\AppConfigUpdatedTransfer;

interface AppConfigUpdatedMessageHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AppConfigUpdatedTransfer $appConfigUpdatedTransfer
     *
     * @return void
     */
    public function handleAppConfigUpdatedTransfer(AppConfigUpdatedTransfer $appConfigUpdatedTransfer): void;
}
