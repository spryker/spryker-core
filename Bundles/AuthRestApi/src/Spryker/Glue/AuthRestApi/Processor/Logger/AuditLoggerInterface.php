<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\Logger;

use Generated\Shared\Transfer\OauthRequestTransfer;

interface AuditLoggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return void
     */
    public function addFailedLoginAuditLog(OauthRequestTransfer $oauthRequestTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return void
     */
    public function addSuccessfulLoginAuditLog(OauthRequestTransfer $oauthRequestTransfer): void;
}
