<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Mapper;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;

class PickingListsSalesOrdersBackendResourceRelationshipMapper implements PickingListsSalesOrdersBackendResourceRelationshipMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     * @param \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRelationshipTransfer
     */
    public function mapGlueResourceTransferToGlueRelationshipTransfer(
        GlueResourceTransfer $glueResourceTransfer,
        GlueRelationshipTransfer $glueRelationshipTransfer
    ): GlueRelationshipTransfer {
        return $glueRelationshipTransfer->addResource($glueResourceTransfer);
    }
}
