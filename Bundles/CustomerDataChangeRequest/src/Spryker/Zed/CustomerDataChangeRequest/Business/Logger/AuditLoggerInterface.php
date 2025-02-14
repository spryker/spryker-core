<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Logger;

interface AuditLoggerInterface
{
    /**
     * @return void
     */
    public function addSuccessfulEmailUpdateAuditLog(): void;
}
