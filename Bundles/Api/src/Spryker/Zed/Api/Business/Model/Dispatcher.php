<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiOptionsTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Executor\ResourcePluginExecutorInterface;
use Spryker\Zed\Api\Business\Mapper\ApiResponseMapperInterface;
use Spryker\Zed\Api\Business\Model\Validator\ApiValidatorInterface;
use Throwable;

class Dispatcher implements DispatcherInterface
{
    /**
     * @var \Spryker\Zed\Api\Business\Executor\ResourcePluginExecutorInterface
     */
    protected $resourcePluginExecutor;

    /**
     * @var \Spryker\Zed\Api\Business\Model\ProcessorInterface
     */
    protected $processor;

    /**
     * @var \Spryker\Zed\Api\Business\Model\Validator\ApiValidatorInterface
     */
    protected $apiValidator;

    /**
     * @var \Spryker\Zed\Api\Business\Mapper\ApiResponseMapperInterface
     */
    protected $apiResponseMapper;

    /**
     * @param \Spryker\Zed\Api\Business\Executor\ResourcePluginExecutorInterface $resourcePluginExecutor
     * @param \Spryker\Zed\Api\Business\Model\ProcessorInterface $processor
     * @param \Spryker\Zed\Api\Business\Model\Validator\ApiValidatorInterface $apiValidator
     * @param \Spryker\Zed\Api\Business\Mapper\ApiResponseMapperInterface $apiResponseMapper
     */
    public function __construct(
        ResourcePluginExecutorInterface $resourcePluginExecutor,
        ProcessorInterface $processor,
        ApiValidatorInterface $apiValidator,
        ApiResponseMapperInterface $apiResponseMapper
    ) {
        $this->resourcePluginExecutor = $resourcePluginExecutor;
        $this->processor = $processor;
        $this->apiValidator = $apiValidator;
        $this->apiResponseMapper = $apiResponseMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function dispatch(ApiRequestTransfer $apiRequestTransfer)
    {
        $apiRequestTransfer = $this->processor->preProcess($apiRequestTransfer);

        $apiResponseTransfer = $this->dispatchToResource($apiRequestTransfer);

        $apiResponseTransfer = $this->processor->postProcess($apiRequestTransfer, $apiResponseTransfer);

        if ($apiResponseTransfer->getCode() === null) {
            $apiResponseTransfer->setCode(ApiConfig::HTTP_CODE_SUCCESS);
        }

        return $apiResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    protected function dispatchToResource(ApiRequestTransfer $apiRequestTransfer): ApiResponseTransfer
    {
        $apiResponseTransfer = new ApiResponseTransfer();

        try {
            $apiValidationErrorTransfers = $this->getValidationErrors($apiRequestTransfer);
            if ($apiValidationErrorTransfers !== []) {
                return $this->apiResponseMapper->mapValidationErrorTransfersToApiResponseTransfer(new ArrayObject($apiValidationErrorTransfers), $apiResponseTransfer);
            }

            $apiPluginCallResponseTransfer = $this->resourcePluginExecutor->execute(
                $apiRequestTransfer->getResourceOrFail(),
                $apiRequestTransfer->getResourceActionOrFail(),
                $apiRequestTransfer->getResourceId(),
                $apiRequestTransfer->getResourceParameters(),
            );
            $apiResponseTransfer->setType(get_class($apiPluginCallResponseTransfer));

            if ($apiPluginCallResponseTransfer instanceof ApiOptionsTransfer) {
                return $apiResponseTransfer->setOptions($apiPluginCallResponseTransfer->getOptions());
            }

            if ($apiPluginCallResponseTransfer instanceof ApiCollectionTransfer) {
                return $this->apiResponseMapper->mapApiCollectionTransferToApiResponseTransfer($apiPluginCallResponseTransfer, $apiResponseTransfer);
            }

            if ($apiPluginCallResponseTransfer instanceof ApiItemTransfer) {
                return $this->apiResponseMapper->mapApiItemTransferToApiResponseTransfer($apiPluginCallResponseTransfer, $apiResponseTransfer);
            }
        } catch (Throwable $e) {
            $apiResponseTransfer->setCode($this->resolveStatusCode((int)$e->getCode()));
            $apiResponseTransfer->setMessage($e->getMessage());
            $apiResponseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
        }

        return $apiResponseTransfer;
    }

    /**
     * @param int $code
     *
     * @return int
     */
    protected function resolveStatusCode($code)
    {
        if ($code < ApiConfig::HTTP_CODE_SUCCESS || $code > ApiConfig::HTTP_CODE_INTERNAL_ERROR) {
            return ApiConfig::HTTP_CODE_INTERNAL_ERROR;
        }

        return $code;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    protected function getValidationErrors(ApiRequestTransfer $apiRequestTransfer): array
    {
        $resourceParameters = $apiRequestTransfer->getResourceParameters();

        $apiDataTransfer = null;
        foreach ($resourceParameters as $resourceParameter) {
            if (!$resourceParameter instanceof ApiDataTransfer) {
                continue;
            }

            $apiDataTransfer = $resourceParameter;

            break;
        }

        if ($apiDataTransfer === null) {
            return [];
        }

        $apiRequestTransfer->setApiData($apiDataTransfer);

        return $this->apiValidator->validate($apiRequestTransfer);
    }
}
