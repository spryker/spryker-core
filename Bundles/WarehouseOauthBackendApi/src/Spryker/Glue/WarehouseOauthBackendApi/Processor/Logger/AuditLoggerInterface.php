<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger;

use Generated\Shared\Transfer\GlueRequestTransfer;

interface AuditLoggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addWarehouseUserFailedLoginAuditLog(GlueRequestTransfer $glueRequestTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addWarehouseUserSuccessfulLoginAuditLog(GlueRequestTransfer $glueRequestTransfer): void;
}
