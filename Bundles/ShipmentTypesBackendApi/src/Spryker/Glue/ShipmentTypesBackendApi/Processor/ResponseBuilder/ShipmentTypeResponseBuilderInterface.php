<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

interface ShipmentTypeResponseBuilderInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createShipmentTypeResponse(
        ArrayObject $shipmentTypeTransfers,
        ?PaginationTransfer $paginationTransfer = null
    ): GlueResponseTransfer;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createErrorResponse(ArrayObject $errorTransfers, ?string $localeName = null): GlueResponseTransfer;
}
