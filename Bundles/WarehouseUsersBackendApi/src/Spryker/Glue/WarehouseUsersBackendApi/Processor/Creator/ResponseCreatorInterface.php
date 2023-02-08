<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator;

use ArrayObject;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

interface ResponseCreatorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseUserAssignmentResponse(
        ArrayObject $warehouseUserAssignmentTransfers,
        ?PaginationTransfer $paginationTransfer = null
    ): GlueResponseTransfer;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseUserAssignmentErrorResponse(ArrayObject $errorTransfers): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseUserAssignmentNotFoundErrorResponse(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseUserAssignmentForbiddenErrorResponse(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createNoContentResponse(): GlueResponseTransfer;
}
