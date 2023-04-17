<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;

interface PickingListsSalesShipmentsResourceRelationshipExpanderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addPickingListItemsSalesShipmentsRelationships(array $glueResourceTransfers, GlueRequestTransfer $glueRequestTransfer): void;
}
