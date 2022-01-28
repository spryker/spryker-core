<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiMetaTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;

class ApiResponseMapper implements ApiResponseMapperInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_VALIDATION_ERRORS = 'Validation errors.';

    /**
     * @param \Generated\Shared\Transfer\ApiCollectionTransfer $apiCollectionTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function mapApiCollectionTransferToApiResponseTransfer(
        ApiCollectionTransfer $apiCollectionTransfer,
        ApiResponseTransfer $apiResponseTransfer
    ): ApiResponseTransfer {
        if ($apiCollectionTransfer->getValidationErrors()->count()) {
            return $this->mapValidationErrorTransfersToApiResponseTransfer($apiCollectionTransfer->getValidationErrors(), $apiResponseTransfer);
        }

        if ($apiCollectionTransfer->getStatusCode() !== null) {
            $apiResponseTransfer->setCode($apiCollectionTransfer->getStatusCode());
        }

        return $apiResponseTransfer->fromArray($apiCollectionTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiItemTransfer $apiItemTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function mapApiItemTransferToApiResponseTransfer(
        ApiItemTransfer $apiItemTransfer,
        ApiResponseTransfer $apiResponseTransfer
    ): ApiResponseTransfer {
        if ($apiItemTransfer->getValidationErrors()->count()) {
            return $this->mapValidationErrorTransfersToApiResponseTransfer($apiItemTransfer->getValidationErrors(), $apiResponseTransfer);
        }

        if ($apiItemTransfer->getStatusCode() !== null) {
            $apiResponseTransfer->setCode($apiItemTransfer->getStatusCode());
        }

        $apiResponseTransfer->setMeta($this->mapApiItemTransferToApiMetaTransfer($apiItemTransfer, new ApiMetaTransfer()));

        return $apiResponseTransfer->fromArray($apiItemTransfer->toArray(), true);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ApiValidationErrorTransfer> $apiValidationErrorTransfers
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function mapValidationErrorTransfersToApiResponseTransfer(
        ArrayObject $apiValidationErrorTransfers,
        ApiResponseTransfer $apiResponseTransfer
    ): ApiResponseTransfer {
        return $apiResponseTransfer
            ->setCode(ApiConfig::HTTP_CODE_VALIDATION_ERRORS)
            ->setMessage(static::MESSAGE_VALIDATION_ERRORS)
            ->setValidationErrors($apiValidationErrorTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiItemTransfer $apiItemTransfer
     * @param \Generated\Shared\Transfer\ApiMetaTransfer $apiMetaTransfer
     *
     * @return \Generated\Shared\Transfer\ApiMetaTransfer
     */
    public function mapApiItemTransferToApiMetaTransfer(
        ApiItemTransfer $apiItemTransfer,
        ApiMetaTransfer $apiMetaTransfer
    ): ApiMetaTransfer {
        return $apiMetaTransfer->setResourceId($apiItemTransfer->getId());
    }
}
