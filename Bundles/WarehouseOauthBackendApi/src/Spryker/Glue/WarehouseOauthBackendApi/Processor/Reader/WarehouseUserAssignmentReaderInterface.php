<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;

interface WarehouseUserAssignmentReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer|null
     */
    public function findActiveWarehouseUserAssignment(GlueRequestTransfer $glueRequestTransfer): ?WarehouseUserAssignmentTransfer;
}
