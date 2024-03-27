<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Sender;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;

interface RequestStatusChangeMailNotificationSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    public function sendRequestStatusChangeMailNotification(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void;
}
