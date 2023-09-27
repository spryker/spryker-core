<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Creator;

use ArrayObject;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface PickingListResponseCreatorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPickingListSuccessfulResponse(
        ArrayObject $pickingListTransferCollection
    ): GlueResponseTransfer;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPickingListErrorResponse(ArrayObject $errorTransfers, ?string $localeName): GlueResponseTransfer;

    /**
     * @param string $message
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPickingListSingleErrorResponse(string $message, ?string $localeName): GlueResponseTransfer;
}
