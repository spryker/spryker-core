<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Sender;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipDeleteMailNotificationSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function sendMerchantRelationshipDeleteMailNotification(MerchantRelationshipTransfer $merchantRelationshipTransfer): void;
}
