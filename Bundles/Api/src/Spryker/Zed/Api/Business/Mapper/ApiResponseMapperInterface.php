<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;

interface ApiResponseMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiCollectionTransfer $apiCollectionTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function mapApiCollectionTransferToApiResponseTransfer(
        ApiCollectionTransfer $apiCollectionTransfer,
        ApiResponseTransfer $apiResponseTransfer
    ): ApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ApiItemTransfer $apiItemTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function mapApiItemTransferToApiResponseTransfer(
        ApiItemTransfer $apiItemTransfer,
        ApiResponseTransfer $apiResponseTransfer
    ): ApiResponseTransfer;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiValidationErrorTransfer> $apiValidationErrorTransfers
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function mapValidationErrorTransfersToApiResponseTransfer(
        ArrayObject $apiValidationErrorTransfers,
        ApiResponseTransfer $apiResponseTransfer
    ): ApiResponseTransfer;
}
